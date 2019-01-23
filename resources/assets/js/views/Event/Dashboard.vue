<template>
    <div class="listing mt20">

        <div class="listing__item listing__item--dash grid">
            <!-- Statut de l’épreuve -->
            <div class="box">
                <span class="listing__eventTitle">Statut de l’épreuve</span>
                <span v-if="event.status == 1" class="warning">{{ event.status | formatStatus }}</span>
                <span v-else-if="event.status == 2" class="success">{{ event.status | formatStatus }}</span>
                <span v-else>{{ event.status | formatStatus }}</span>
            </div>

            <!-- Nombre d’étudiants inscrit -->
            <div class="box">
                <span class="listing__eventTitle">Nombre d’étudiants inscrit</span>
                <span class="listing__span">{{ event.students.length }}</span>
            </div>

            <!-- Nombre de membres du jury inscrit -->
            <div class="box">
                <span class="listing__eventTitle">Nombre de membres du jury inscrit</span>
                <span class="listing__span">{{ event.users.length }}</span>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                event: []
            }
        },

        mounted() {
            this.getEvent();
        },

        methods: {
            getEvent() {
                this.loading = true;
                axios.get(`/api/events/${this.$route.params.id}`)
                    .then(response => {
                        this.event = response.data;
                        window.document.title = response.data.course_name + ' - ' + response.data.academic_year + ' - Session ' + response.data.exam_session + ' - Jiri';
                        document.getElementById('header-title').innerHTML = response.data.course_name + ' - ' + response.data.academic_year + ' - Session ' + response.data.exam_session;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => this.loading = false);
            },
        },

        filters: {
            formatStatus: function (value) {
                if (value == 0) {
                    return 'En attente'
                } else if (value == 1) {
                    return 'En cours'
                } else if (value == 2) {
                    return 'Terminé'
                }
            }
        },
    }
</script>

<style scoped>

</style>
