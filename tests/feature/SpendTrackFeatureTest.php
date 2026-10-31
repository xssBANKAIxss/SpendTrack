<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * End-to-end HTTP checks against the isolated SQLite test database.
 *
 * @internal
 */
final class SpendTrackFeatureTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'Tests\\Support';

    public function testPublicPagesAreAvailable(): void
    {
        foreach (['/', '/login', '/register', '/forgot-password', '/forgot-pin'] as $path) {
            $this->get($path)->assertOK();
        }
    }

    public function testProtectedPagesRequireAuthentication(): void
    {
        foreach (['/expenses/create', '/expenses/index', '/reports/index', '/index'] as $path) {
            $this->get($path)->assertRedirectTo('/login');
        }
    }

    public function testAdminPagesRequireAnAdministrator(): void
    {
        $this->get('/admin/dashboard')->assertRedirectTo('/login');

        $this->withSession(['user_id' => 1, 'role' => 'user'])
            ->get('/admin/dashboard')
            ->assertRedirectTo('/index');
    }

    public function testAdministratorCanOpenAdministrationPages(): void
    {
        $adminId = $this->createUser('admin@example.com', 'admin');
        $session = ['user_id' => $adminId, 'role' => 'admin'];

        foreach (['/admin/dashboard', '/admin/users', '/admin/expenses'] as $path) {
            $this->withSession($session)->get($path)->assertOK();
        }
    }

    public function testExpenseValidationRejectsIncompleteData(): void
    {
        $userId = $this->createUser('validation@example.com');
        $response = $this->withSession(['user_id' => $userId, 'role' => 'user'])
            ->post('/expenses/store', [
                csrf_token() => csrf_hash(),
                'title' => '',
                'amount' => 'not-a-number',
                'category' => '',
                'expense_date' => 'not-a-date',
            ]);

        $response->assertRedirectTo(base_url('expenses/create'));
        $this->seeNumRecords(0, 'expenses', []);
    }

    public function testExpenseCannotBeEditedOrDeletedByAnotherUser(): void
    {
        $ownerId = $this->createUser('owner@example.com');
        $otherId = $this->createUser('other@example.com');
        $expenseId = $this->createExpense($ownerId, 'Private expense', 120.50, 'Food');

        $this->withSession(['user_id' => $otherId, 'role' => 'user'])
            ->get('/expenses/edit/' . $expenseId)
            ->assertRedirectTo(base_url('expenses/index'));

        $this->withSession(['user_id' => $otherId, 'role' => 'user'])
            ->post('/expenses/delete/' . $expenseId, [csrf_token() => csrf_hash()])
            ->assertRedirectTo(base_url('expenses/index'));

        $this->seeInDatabase('expenses', ['id' => $expenseId, 'user_id' => $ownerId]);
    }

    public function testUserCanCreateUpdateAndDeleteTheirOwnExpense(): void
    {
        $userId = $this->createUser('crud@example.com');
        $session = ['user_id' => $userId, 'role' => 'user'];

        $createResponse = $this->withSession($session)->post('/expenses/store', [
            csrf_token() => csrf_hash(),
            'title' => 'Coffee',
            'amount' => '120.50',
            'category' => 'Food',
            'expense_date' => date('Y-m-d'),
            'notes' => 'Morning coffee',
        ]);
        $createResponse->assertRedirectTo(base_url('expenses/create'));
        $this->seeInDatabase('expenses', ['user_id' => $userId, 'title' => 'Coffee']);

        $expenseId = (int) $this->db->table('expenses')->select('id')->where('title', 'Coffee')->get()->getRow()->id;
        $updateResponse = $this->withSession($session)->post('/expenses/update/' . $expenseId, [
            csrf_token() => csrf_hash(),
            'title' => 'Lunch',
            'amount' => '220.00',
            'category' => 'Food',
            'expense_date' => date('Y-m-d'),
            'notes' => 'Updated expense',
        ]);
        $updateResponse->assertRedirectTo(base_url('expenses/index'));
        $this->seeInDatabase('expenses', ['id' => $expenseId, 'title' => 'Lunch', 'amount' => 220]);

        $deleteResponse = $this->withSession($session)->post('/expenses/delete/' . $expenseId, [
            csrf_token() => csrf_hash(),
        ]);
        $deleteResponse->assertRedirectTo(base_url('expenses/index'));
        $this->dontSeeInDatabase('expenses', ['id' => $expenseId]);
    }

    public function testLoggedInUserSeesOnlyTheirExpensesAndReports(): void
    {
        $memberId = $this->createUser('member@example.com');
        $otherId = $this->createUser('other@example.com');
        $this->createExpense($memberId, 'My lunch', 75.00, 'Food');
        $this->createExpense($otherId, 'Other purchase', 500.00, 'Travel');

        $session = ['user_id' => $memberId, 'role' => 'user'];
        $expenseResponse = $this->withSession($session)->get('/expenses/index');
        $expenseResponse->assertOK();
        $expenseResponse->assertSee('My lunch');
        $expenseResponse->assertDontSee('Other purchase');

        $reportResponse = $this->withSession($session)->get('/reports/index');
        $reportResponse->assertOK();
        $reportResponse->assertSee('75.00');
        $reportResponse->assertDontSee('500.00');

        $dashboardResponse = $this->withSession($session)->get('/index');
        $dashboardResponse->assertOK();
        $dashboardResponse->assertSee('75.00');
        $dashboardResponse->assertDontSee('500.00');
    }

    private function createUser(string $email, string $role = 'user'): int
    {
        $this->db->table('users')->insert([
            'name' => 'Test User',
            'email' => $email,
            'password' => password_hash('Valid!123', PASSWORD_DEFAULT),
            'pin' => password_hash('1234', PASSWORD_DEFAULT),
            'is_verified' => 1,
            'otp_verified_at' => date('Y-m-d H:i:s'),
            'role' => $role,
        ]);

        return (int) $this->db->insertID();
    }

    private function createExpense(int $userId, string $title, float $amount, string $category): int
    {
        $this->db->table('expenses')->insert([
            'user_id' => $userId,
            'title' => $title,
            'amount' => $amount,
            'category' => $category,
            'expense_date' => date('Y-m-d'),
        ]);

        return (int) $this->db->insertID();
    }
}
