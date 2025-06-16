document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.querySelector('li.dropdown > a');
    if(dropdown){
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            var parent = this.parentElement;
            parent.classList.toggle('active');
            // Đóng dropdown nếu click ra ngoài
            document.addEventListener('click', function handler(event) {
                if (!parent.contains(event.target)) {
                    parent.classList.remove('active');
                    document.removeEventListener('click', handler);
                }
            });
        });
    }
});