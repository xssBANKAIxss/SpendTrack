<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>© SpendTrack 2026</span>
    </div>
  </div>
</footer>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>

<!-- Chart.js -->
<script src="<?= base_url('assets/vendor/chart.js/Chart.min.js') ?>"></script>

<?php if (session()->get('user_id')): ?>
  <script>
    setInterval(function() {
      fetch('<?= base_url('check-session') ?>')
        .then(function(res) {
          return res.json();
        })
        .then(function(data) {
          if (!data.valid) {
            window.location.href = '<?= base_url('login') ?>?notice=' + encodeURIComponent(data.message);
          }
        });
    }, 5000);
  </script>
<?php endif; ?>

<?= $this->renderSection('scripts') ?>

</body>

</html>