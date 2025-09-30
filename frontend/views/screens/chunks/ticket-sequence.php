<template x-if="hasSequence">
    <div class="info__columns">
        <div class="info__col">
            <span class="info__col-status status status--busy">На&nbsp;приёме</span>
            <ul>
                <template x-for="appointment in busySequence">
                    <template x-if="appointment.ticketCode">
                        <li class="info__col-item" x-text="appointment.ticketCode"></li>
                    </template>
                </template>
            </ul>
        </div>
        <div class="info__col">
            <span class="info__col-status status">Ожидают</span>
            <ol class="info__col-list">
                <template x-for="appointment in waitSequence">
                    <template x-if="appointment.ticketCode">
                        <li class="info__col-item" x-text="appointment.ticketCode"></li>
                    </template>
                </template>
            </ol>
        </div>
    </div>
</template>
