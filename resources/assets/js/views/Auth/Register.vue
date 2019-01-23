<template>
	<div>
		<h1 role="heading" aria-level="1">Page Register</h1>
		<!--<router-link to="/dashboard">Go to Dashboard</router-link>-->
		
		<el-row class="loginForm">
			<el-form>
				<!-- Name -->
				<el-form-item label="Nom">
					<el-input v-model="registerForm.name"></el-input>
				</el-form-item>
				<!-- Adresse e-mail -->
				<el-form-item label="Adresse email">
					<el-input v-model="registerForm.email"></el-input>
				</el-form-item>
				<!-- Mot de passe -->
				<el-form-item label="Mot de passe">
					<el-input v-model="registerForm.password" type="password"></el-input>
				</el-form-item>
				<!-- Confirmation du mot de passe -->
				<el-form-item label="Confirmer le mot de passe">
					<el-input v-model="registerForm.password_confirmation" type="password"></el-input>
				</el-form-item>
				<!-- Submit -->
				<el-form-item>
					<el-button type="primary" @click="register">S’incrire comme administrateur</el-button>
				</el-form-item>
			</el-form>
		</el-row>
	
	</div>
</template>

<script>
  export default {
    data() {
      return {
        registerForm: new JiriForm({
          name: null,
          email: null,
          password: null,
          password_confirmation: null
        }),
      }
    },

    methods: {
      register() {
        axios.post('/api/register', this.registerForm)
            .then(response => {
              console.log(response);
            }).catch(error => {
          console.log(error);
        })
      }
    }
  }
</script>

<style scoped>

</style>
