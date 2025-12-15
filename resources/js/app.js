import './bootstrap';
import 'animate.css';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

// Toast Configuration
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    background: '#fff', // Solid White for maximum readability
    customClass: {
        popup: 'shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-xl border-0 !p-4 !max-w-[90vw] sm:!max-w-sm', // Beautiful soft shadow
        title: 'font-sans font-bold text-slate-800 text-sm',
        timerProgressBar: 'opacity-50'
    },
    showClass: {
        popup: 'animate__animated animate__slideInRight animate__faster' // Slide looks cleaner than Fade
    },
    hideClass: {
        popup: 'animate__animated animate__slideOutRight animate__faster'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Toast = Toast;