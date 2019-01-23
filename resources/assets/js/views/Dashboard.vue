<template>
	<div>
		<p class="dash__intro">
			Jiri est une application qui permet de tenir un jury.
			Par exemple, les cours de Design Web du 2ème bloc et de Projets Web du 3ème
			bloc du bachelier en infographie organisé à la Haute École de la Province de Liège se concluent
			par des jurys lors desquels les travaux des étudiants sont évalués par des externes.
		</p>
		</div>
</template>

<script>
    export default {
        data() {
          return {
          
					}
				},
			
				methods: {
          logout() {
            axios.get('/api/test')
								.then(response => {
								  console.log(response);
								});
					}
				}
    }
</script>

<style scoped>

</style>
