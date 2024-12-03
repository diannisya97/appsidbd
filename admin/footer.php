<footer class="py-4 bg-light mt-auto">
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between small">
        <div class="text-muted"> dikodein &copy; SIDBD Tasikmalaya <script>document.write(new Date().getFullYear())</script></div>
    </div>
    
<!-- Tambahkan Script untuk Menginisialisasi Tooltip -->
<script>
document.querySelectorAll('.tooltip-custom').forEach(item => {
    item.addEventListener('mousemove', function (e) {
        const tooltip = this.querySelector('::after');
        if (tooltip) {
            this.style.setProperty('--tooltip-x', `${e.pageX}px`);
            this.style.setProperty('--tooltip-y', `${e.pageY - 20}px`);
        }
    });

    item.addEventListener('mouseover', function () {
        const tooltipText = this.getAttribute('title');
        const tooltip = document.createElement('div');
        tooltip.className = 'dynamic-tooltip';
        tooltip.innerText = tooltipText;
        document.body.appendChild(tooltip);
    });

    item.addEventListener('mouseout', function () {
        document.querySelectorAll('.dynamic-tooltip').forEach(tooltip => tooltip.remove());
    });
});

</script>
</div>
</footer>