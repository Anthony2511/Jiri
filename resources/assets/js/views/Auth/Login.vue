<template>
	<div>
		<h1 role="heading" aria-level="1">Page Login</h1>
		<!--<router-link to="/dashboard">Go to Dashboard</router-link>-->
		
		<el-row class="loginForm">
			<el-form>
				<!-- Adresse e-mail -->
				<el-form-item label="Adresse e-mail">
					<el-input v-model="loginForm.email"></el-input>
				</el-form-item>
				<!-- Mot de passe -->
				<el-form-item label="Mot de passe">
					<el-input v-model="loginForm.password" type="password"></el-input>
				</el-form-item>
				<!-- Submit -->
				<el-form-item>
					<el-button type="primary" @click="login">Se connecter</el-button>
				</el-form-item>
			</el-form>
		</el-row>
	
	</div>
</template>

<script>
  export default {
    data() {
      return {
        loginForm: new JiriForm({
          email: null,
          password: null
        }),
      }
    },

    methods: {
      login() {
        axios.post('/api/login', this.loginForm)
            .then(response => {
              console.log(response);
              auth.login(response.token, response.user);
            }).catch(error => {
          	console.log(error);
        })
      }
    }
  }
</script>

<style scoped>

</style>
