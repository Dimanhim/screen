<template x-if="roomSequence">
    <ul class="info__list">
        <template x-for="appointment in roomSequence">
            <template x-if="appointment.status_id === 3 || appointment.status_id === 4">
                <li class="info__list-item">
                    <span class="info__list-status status" :class="appointment.status_id === 3 && 'status--busy'" x-text="getAppointmentStatusText(appointment.status_id)"></span>
                    <b class="info__list-time">
                        <span x-text="appointment.time_start"></span>
                    </b>
                    Пациент №
                    <span x-text="appointment.patientNumber"></span>
                </li>
            </template>
        </template>
    </ul>
</template>

