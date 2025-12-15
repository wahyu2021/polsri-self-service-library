import './bootstrap';
import 'animate.css';
import 'sweetalert2/dist/sweetalert2.min.css';
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
    background: '#fff',
    customClass: {
        popup: 'bg-white/95 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] rounded-2xl border border-slate-100 !p-4 !max-w-[90vw] sm:!max-w-sm',
        title: 'font-sans font-bold text-slate-800 text-sm ml-2',
        timerProgressBar: 'opacity-30 rounded-b-2xl'
    },
    showClass: {
        popup: 'animate__animated animate__fadeInRight animate__fast'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutRight animate__fast'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Toast = Toast;