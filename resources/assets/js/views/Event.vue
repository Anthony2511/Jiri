<template>
	<div>
		
		<router-view></router-view>
		
	</div>
</template>

<script>
	import EventDashboard from './Event/Dashboard';
	
	export default {
		components: {
        'EventDashboard': EventDashboard
		},
		
		data() {
			return {
			    event: [],
					loading: false
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
				}
		}
	}
</script>
