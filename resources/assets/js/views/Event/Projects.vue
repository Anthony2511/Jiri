<template>
	<div>
		<div class="top-form grid grid-sb" style="margin-left: 10px;">
			<el-row :gutter="20" style="width: 100%;">
				<div class=" listing__ponderation-totale grid grid-sb">
					
						<el-button @click="dialogAddProject = true" type="primary" icon="el-icon-plus" plain>Ajouter un projet</el-button>
						<div class="pronderation-totale">
							<el-popover
								placement="top-start"
								width="200"
								trigger="hover"
								content="Vous devez atteindre 100%">
								<strong slot="reference">
									Pondération totale :
									<span v-if="totalPonderation == 100" class="ponderationOk">{{ totalPonderation }}%</span>
									<span v-else class="ponderationError">{{ totalPonderation }}%</span>
								</strong>
							</el-popover>
						</div>
					
				</div>
			</el-row>
		</div>
		
		<!-- LISTING -->
		<div class="listing mt20" v-loading="this.loading">
			<div class="listing__item grid" v-for="project in projects">
				<div class="t20 col--30"><span class="listing__eventTitle">{{ project.name }}</span></div>
				<div class="t20 col--10"><span class="listing__ponderation">{{ project.ponderation_default }}%</span></div>
				<div class="t20 col--40"><span class="listing__text">{{ project.description | exerptText }}</span></div>
				<div class="listing__button col--20">
					<!-- Button Edit -->
					<el-button icon="el-icon-edit" size="small" @click="editProject(project)"></el-button>
					<!-- Button Delte -->
					<el-popover placement="top" width="220">
						<p class="el-popover__text">Voulez-vous vraiment supprimer <strong>{{ deletingProject.name }}</strong> ?</p>
						<div class="el-popover__actions">
							<el-button size="mini" type="text" onclick="$('.el-popover').css('display', 'none');">Annuler</el-button>
							<el-button onclick="$('.el-popover').css('display', 'none');" @click="destroyProject" type="danger" size="mini">Supprimer</el-button>
						</div>
						<el-button slot="reference" @click="deleteGivenProject(project)" type="danger" size="small" plain icon="el-icon-delete"></el-button>
					</el-popover>
				</div>
			</div>
		</div>
		<!-- END LISTING -->
		
		<!-- Dialog Create Project -->
		<el-dialog :visible.sync="dialogAddProject" :lock-scroll="true" title="Ajouter un projet">
			<el-form :model="addProject" class="dialog-simple-form" label-position="left" label-width="200px">
				<el-row>
					<!-- Name -->
					<el-form-item label="Nom" required :error="addProject.errors.get('name')">
						<el-input v-model="addProject.name" name="name" id="name"></el-input>
					</el-form-item>
					<!-- Nom raccourcis -->
					<el-form-item label="Nom raccourcis" required :error="addProject.errors.get('name_short')">
						<el-input v-model="addProject.name_short" name="name" id="name_short"></el-input>
					</el-form-item>
					<!-- Description -->
					<el-form-item label="Description" required :error="addProject.errors.get('description')">
						<el-input type="textarea" :rows="2" v-model="addProject.description" name="description" id="description"></el-input>
					</el-form-item>
					<!-- Pondération par défault -->
					<el-form-item label="Pondération (par défaut, en %)" required :error="addProject.errors.get('ponderation_default')">
						<el-input-number style="width: 100%;" v-model="addProject.ponderation_default" controls-position="right" :step="10" :min="0" :max="100" id="ponderation_default"></el-input-number>
					</el-form-item>
					<!-- Boutons -->
					<el-button @click="dialogAddProject = false">Annuler</el-button>
					<el-button type="warning" :loading="addProject.busy" @click="store">
						<span v-if="addProject.busy">Ajout en cours…</span>
						<span v-else>Ajouter</span>
					</el-button>
				</el-row>
			</el-form>
		</el-dialog>
		
		<!-- Dialog Update Project -->
		<el-dialog :visible.sync="dialogUpdateProject" :lock-scroll="true" title="Modifier le projet">
			<el-form :model="updateProject" class="dialog-simple-form" label-position="left" label-width="200px">
				<el-row>
					<!-- Name -->
					<el-form-item label="Nom" required :error="updateProject.errors.get('name')">
						<el-input v-model="updateProject.name" name="name"></el-input>
					</el-form-item>
					<!-- Nom raccourcis -->
					<el-form-item label="Nom raccourcis" required :error="updateProject.errors.get('name_short')">
						<el-input v-model="updateProject.name_short" name="name"></el-input>
					</el-form-item>
					<!-- Description -->
					<el-form-item label="Description" required :error="updateProject.errors.get('description')">
						<el-input type="textarea" :rows="2" v-model="updateProject.description" name="description"></el-input>
					</el-form-item>
					<!-- Pondération par défault -->
					<el-form-item label="Pondération (par défaut, en %)" required :error="updateProject.errors.get('ponderation_default')">
						<el-input-number style="width: 100%;" v-model="updateProject.ponderation_default" controls-position="right" :step="10" :min="0" :max="100"></el-input-number>
					</el-form-item>
					<!-- Boutons -->
					<el-button @click="dialogUpdateProject = false">Annuler</el-button>
					<el-button type="warning" :loading="updateProject.busy" @click="update">
						<span v-if="updateProject.busy">Modification en cours…</span>
						<span v-else>Modifier</span>
					</el-button>
				</el-row>
			</el-form>
		</el-dialog>
	</div>
</template>

<script>
    export default {
        data() {
            return {
              	event: [],
								dialogAddProject: false,
								addProject: new JiriForm({
										name: '',
										name_short: '',
										description: '',
										ponderation_default: '',
										event_id: this.$route.params.id
								}),
								loading: true,
								projects: [],
								dialogUpdateProject: false,
								updateProject: new JiriForm({
                    name: '',
                    name_short: '',
                    description: '',
                    ponderation_default: '',
                    event_id: this.$route.params.id
								}),
								updatingProject: new JiriForm({}),
								deletingProject: new JiriForm({}),
								deleteProject: new JiriForm({
										popoverDeleteProject: false
								})
						}
				},
				
				mounted() {
            var self = this;
            
            this.getEvent();
            this.getProjects();
            this.$on('updateListProjects', () => {
               self.getProjects();
						});
				},
				
				computed: {
          totalPonderation: function() {
              return this.projects.reduce((acc, item) => acc + item.ponderation_default, 0);
					}
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
            
            getProjects() {
                this.loading = true;

                axios.get(`/api/events/${this.$route.params.id}/projects`)
                    .then( response => {
                        this.projects = response.data;
                    } )
                    .catch( error => {
                        console.log( error );
                    } )
                    .finally( () => this.loading = false );
						},
            
            /* Store a new project in database */
            store() {
                Jiri.post(`/api/events/${this.$route.params.id}/projects`, this.addProject)
                    .then( response => {
                        this.$emit('updateListProjects');
                        this.dialogAddProject = false;
                        this.addProject.name = '',
												this.addProject.name_short = '',
												this.addProject.description = '',
												this.addProject.ponderation_default = ''
                    } ).catch( error => {
                    console.log( error )
                } )
            },
						
						editProject(project) {
                this.updatingProject = project;
                this.updateProject.name = project.name;
                this.updateProject.name_short = project.name_short;
                this.updateProject.description = project.description;
                this.updateProject.ponderation_default = project.ponderation_default;
                this.updateProject.event_id = project.event_id;
                this.dialogUpdateProject = true;
						},
						
						update() {
                Jiri.put( `/api/events/${this.$route.params.id}/projects/${this.updatingProject.id}`, this.updateProject )
                    .then( response => {
                        this.$emit( 'updateListProjects' );
                        this.dialogUpdateProject = false;
                        this.$message({
                            message: `Le projet (${this.updatingProject.name}) a été mis à jour.`,
                            type: 'success'
                        });
                    } );
						},

            /*
					* Delete the given type
					*/
            deleteGivenProject(project) {
                this.deletingProject = project;
                this.deleteProject.popoverDeleteProject = true;
            },

            /**
             * Delete the current type
             */
            destroyProject() {
                Jiri.delete(`/api/events/${this.$route.params.id}/projects/${this.deletingProject.id}`, this.deleteProject)
                    .then(() => {
                        this.$emit('updateListProjects');
                        this.$message({
                            message: `Le projet (${this.deletingProject.name}) a été correctement supprimé.`,
                            type: 'success'
                        });
                    });
            },
				},
				
				filters: {
            exerptText: function (value) {
								if (value.length > 50) {
								    return value.substring(0, 50) + '…';
								} else {
								    return value;
								}
            }
				}
    }
</script>

<style scoped>

</style>
