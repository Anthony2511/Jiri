<template>
	<div>
		<div class="grid grid-sb" style="margin-bottom: 30px">
			<span class="page-subtitle">Liste des étudiants</span>
		</div>
		
		<div class="listing mt20" v-loading="this.loading">
			<div class="listing__item grid" v-for="user in studentsList">
				<div class="col--10">
					<avatar v-bind:username="user.name" :size="36" class="col--10"></avatar>
				</div>
				<div class="t20 col--20">{{ user.name }}</div>
				<div class="t20 col--30">{{ user.email }}</div>
				<div class="t20 col--20">{{ user.company }}</div>
				<div class="t20 col--20">
					<router-link :to="{ name: 'StudentProjects', params: { student: user.id }}">
						<el-button icon="el-icon-view"></el-button>
					</router-link>
				</div>
			</div>
		</div>
		
	</div>
</template>

<script>
    import Avatar from 'vue-avatar'
    export default {
        data() {
            return {
              event: [],
							loading: true,
            	studentsList: []
						}
				},
				
				mounted() {
          	this.getEvent();
            this.getStudentsList();
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
          
            getStudentsList() {
                this.loading = true;
                axios.get(`/api/events/${this.$route.params.id}/students/get`)
                    .then(response => {
                        this.studentsList = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => this.loading = false);

            },
				},
				
				components: {
            Avatar
				}
    }
</script>

<style scoped>

</style>
