<template>
	<div>
		<div class="top-form grid grid-sb">
			
			<el-form :inline="true" :model="createUser">
				<el-form-item :error="createUser.errors.get('name')">
					<el-input v-model="createUser.name" placeholder="Nom"></el-input>
				</el-form-item>
				<el-form-item :error="createUser.errors.get('company')">
					<el-input v-model="createUser.company" placeholder="Société"></el-input>
				</el-form-item>
				<el-form-item :error="createUser.errors.get('email')">
					<el-input v-model="createUser.email" type="email" class="input-email" placeholder="Adresse e-mail"></el-input>
				</el-form-item>
				<el-form-item>
					<el-button type="primary" :loading="createUser.busy" @click.prevent="store">Ajouter un juré</el-button>
				</el-form-item>
			</el-form>
			
			<div class="col--25 ">
				<el-input
								placeholder="Chercher un juré"
								suffix-icon="el-icon-search"
								clearable
								@input="searchUsers"
								v-model="keywords">
				</el-input>
			</div>
		
		</div>
		
		<div class="listing mt20" v-loading="this.loading">
			<div class="listing__item grid" v-for="user in users.data">
				<div class="col--10">
					<avatar v-bind:username="user.name" :size="36" class="listing__avatar col--10"></avatar>
				</div>
				<div class="listing__name t20 col--20">{{ user.name }}</div>
				<div class="listing__name t20 col--30">{{ user.email }}</div>
				<div class="listing__name t20 col--30">{{ user.company }}</div>
				
				<div class="listing__button col--10">
					<el-button icon="el-icon-edit" size="small" @click="editUser(user)"></el-button>
					
					
					<el-popover placement="top" width="220">
						<p class="el-popover__text">Voulez-vous vraiment supprimer <strong>{{ deletingUser.name }}</strong> ?</p>
						<div class="el-popover__actions">
							<el-button size="mini" type="text" onclick="$('.el-popover').css('display', 'none');">Annuler</el-button>
							<el-button onclick="$('.el-popover').css('display', 'none');" @click="destroyUser" type="danger" size="mini">Supprimer</el-button>
						</div>
						<el-button slot="reference" @click="deleteGivenUser(user)" type="danger" size="small" plain icon="el-icon-delete"></el-button>
					</el-popover>
				
				
				</div>
			</div>
		</div>
		
		<!-- Pagination -->
		<div class="pagination">
			<el-pagination
							prop="animals"
							v-if="users.total > 15"
							layout="prev, pager, next"
							:disabled="this.loading"
							:page-size="users.per_page"
							@current-change="handleCurrentChange"
							:current-page="users.current_page"
							:total="users.total">
			</el-pagination>
		</div>
		
		<!-- Update a user -->
		<el-dialog :visible.sync="updateUser.dialogUpdateUser" :lock-scroll="true" title="Modifier un juré">
			<el-form :model="updateUser" class="dialog-simple-form" label-position="left" label-width="70px">
				<el-row>
					<el-form-item label="Nom" required :error="updateUser.errors.get('name')">
						<el-input v-model="updateUser.name" name="name" id="name"></el-input>
					</el-form-item>
					<el-form-item label="E-mail" required :error="updateUser.errors.get('email')">
						<el-input v-model="updateUser.email" name="email" id="email"></el-input>
					</el-form-item>
					<el-form-item label="Société" required :error="updateUser.errors.get('company')">
						<el-input v-model="updateUser.company" name="company" id="company"></el-input>
					</el-form-item>
					<el-form-item label="Statut" required :error="updateUser.errors.get('is_active')">
						<el-switch v-model="updateUser.is_active" active-text="Actif" inactive-text="Inactif" :active-value="1" :inactive-value="0">
						</el-switch>
					</el-form-item>
				</el-row>
				
				<el-button @click="updateUser.dialogUpdateUser = false">Annuler</el-button>
				<el-button type="warning" :loading="updateUser.busy" @click="update">
					<span v-if="updateUser.busy">Mise à jour en cours…</span>
					<span v-else>Modifier</span>
				</el-button>
			</el-form>
		</el-dialog>
	
	</div>
</template>

<script>
    import Avatar from 'vue-avatar'
    import debounce from 'lodash.debounce'

    export default {
        data() {
            return {
                createUser: new JiriForm({
                    name: '',
                    email: '',
										company: '',
                }),
                updateUser: new JiriForm({
                    name: '',
                    email: '',
										company: '',
                    is_active: '',
                    dialogUpdateUser: false
                }),
                deleteUser: new JiriForm({
                    popoverDeleteUser: false,
                }),
                loading: true,
                errored: false,
                updatingUser: new JiriForm({ name: null }),
                deletingUser: new JiriForm({ name: null }),
                keywords: '',
                users: []
            }
        },

        directives: { debounce },

        mounted() {
            var self = this;

            this.getUsers();
            this.$on( 'updateListUsers', () => {
                self.getUsers();
            } );
        },

        methods: {
            /*
						 * Get list of users
						 */
            getUsers() {
                this.loading = true;

                axios.get('/api/users', { params: { keywords: this.keywords } })
                    .then( response => {
                        this.users = response.data;
                    } )
                    .catch( error => {
                        console.log( error );
                        this.errored = true;
                    } )
                    .finally( () => this.loading = false );
            },

            /*
						 * Edit the given user
						 */
            editUser(user) {
                this.updatingUser = user;
                this.updateUser.name = user.name;
                this.updateUser.email = user.email;
                this.updateUser.company = user.company;
                this.updateUser.is_active = user.is_active;
                this.updateUser.dialogUpdateUser = true;
            },

            /*
						 * Search a user
						 */
            searchUsers: debounce(function () {
                this.getUsers();
            }, 200),

            /*
						 * Store a new user in database
						 */
            store() {
                Jiri.post( 'api/users', this.createUser )
                    .then( response => {
                        this.$emit( 'updateListUsers' );

                        // Reset form
                        this.createUser.name = '';
                        this.createUser.email = '';

                    } ).catch( error => {
                    console.log( error );
                } );
            },

            /*
						* Update data's of user
						*/
            update() {
                Jiri.put( `api/users/${this.updatingUser.id}`, this.updateUser )
                    .then( response => {
                        this.$emit( 'updateListUsers' );
                        this.updateUser.dialogUpdateUser = false;
                        this.$message({
                            message: `Le juré (${this.updatingUser.name}) a été mis à jour.`,
                            type: 'success'
                        });
                    } );
            },

            /*
						* Delete the given type
						*/
            deleteGivenUser(user) {
                this.deletingUser = user;
                this.deleteUser.popoverDeleteUser = true;
            },

            /**
             * Delete the current type
             */
            destroyUser() {
                Jiri.delete(`api/users/${this.deletingUser.id}`, this.deleteUser)
                    .then(() => {
                        this.$emit('updateListUsers');
                        this.$message({
                            message: `Le juré (${this.deletingUser.name}) a été correctement supprimé.`,
                            type: 'success'
                        });
                    });
            },

            /**
             * Handle change page for pagination
             * @param val
             */
            handleCurrentChange(val) {
                this.loading = true;
                axios.get(`api/users?page=${val}&keywords=${this.keywords}`)
                    .then(response => {
                        this.users = response.data
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => {
                        this.loading = false;
                        $('.el-main').scrollTop(0, 0);
                    })
            },

        },

        components: {
            Avatar
        }
    }
</script>

<style scoped>

</style>
