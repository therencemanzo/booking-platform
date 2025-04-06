<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import { onBeforeMount, onMounted, ref } from 'vue';
import Notification from '@/components/Notification.vue';


const props = defineProps({
    bookings: Object
});

const page = usePage();

const events = ref([]);
const colors = [
  '#FF6B6B', '#F94D6A', '#FF4757', '#FF6348', '#FFA502', '#FFC312', '#FF9F1A', '#F79F1F', '#FF7F50', '#FFB142',
  '#ECCC68', '#FFD32A', '#FF3F34', '#EA2027', '#C44569', '#F368E0', '#D980FA', '#A29BFE', '#706FD3', '#7D5FFF',
  '#5F27CD', '#3B3B98', '#3DC1D3', '#17C0EB', '#00B894', '#00CEC9', '#1DD1A1', '#2ED573', '#10AC84', '#218C74',
  '#2ECC71', '#26DE81', '#20BF6B', '#009432', '#006266', '#1B9CFC', '#0984E3', '#0652DD', '#3742FA', '#5352ED',
  '#4834D4', '#6C5CE7', '#A3CB38', '#C4E538', '#B53471', '#833471', '#ED4C67', '#E84393', '#D980FA', '#FDA7DC',
  '#FFC0CB', '#FF6F91'
];

const notificationMessage = ref('')


const calendarOptions = ref({})
onBeforeMount(() => {

    if(props.bookings.data.length > 0){

        props.bookings.data.forEach(element => {
            let event = {
                title : element.reference_id + ' ' + element.parking_space.name,
                start: new Date(element.date_from),
                end: new Date(element.date_to),
                color: colors[Math.floor(Math.random() * colors.length)]
            }
            events.value.push(event);
        });
    }
   
    calendarOptions.value =  {
            plugins: [ dayGridPlugin ],
            initialView: 'dayGridMonth',
            events: events.value
    };
});

onMounted(()=>{
    
    Echo.private(`App.Models.User.` + page.props.auth.user.id)
    .notification((notification) => {
        if(notification.type == 'new.booking'){
            let event = {
                title : notification.booking.reference_id + ' '+ notification.booking.parking_space.name,
                start: new Date(notification.booking.date_from),
                end: new Date(notification.booking.date_to),
                color: colors[Math.floor(Math.random() * colors.length)]
            }

            notificationMessage.value = notification.booking.reference_id + ' from : ' + notification.booking.date_from + ' to ' + notification.booking.date_to;

            events.value.push(event);

            setTimeout(()=>{
                notificationMessage.value = '';
            }, 3000);
        }
    });
     
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Bookings',
        href: '/dashboard',
    },
];
const today = new Date();

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="notificationMessage">
            <Notification :bookingMessage="notificationMessage"/>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <FullCalendar :options="calendarOptions"/>

            </div>
        </div>
    </AppLayout>
</template>
