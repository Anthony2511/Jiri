<template>
	<div>
		<div class="grid grid-sb" style="margin-bottom: 30px">
			<span class="page-subtitle">Les projets de {{ getStudentData.name }}</span>
			<el-button type="primary" @click="dialogAddImplementation = true;">Ajouter un projet</el-button>
		</div>
		
		<el-row :gutter="20">
			<el-col :span="7" v-for="data in getImplementationsData" class="listing__item listing__item--box listing__item--implementation">
				<div class="grid-content box-wrapper">
					<h2 role="heading" aria-level="2" class="box-implementation-title">{{ data.project.name_short }}</h2>
					<span class="box-implementation-subtitle">URL du projet</span>
					<a href="" target="_blank" class="box-implementation-link">{{ data.url_project }}</a>
					<span class="box-implementation-subtitle">Repository</span>
					<a href="" target="_blank" class="box-implementation-link">{{ data.url_repo }}</a>
					<span class="box-implementation-subtitle">Pondération</span>
					<span class="box-implementation-link">{{ data.ponderation }}</span>
			
					<div class="button-top-right">
						<el-dropdown trigger="click">
							<span class="el-dropdown-link">
								<i class="el-icon-more el-icon--right" style="cursor: pointer"></i>
							</span>
							<el-dropdown-menu slot="dropdown">
								<el-dropdown-item>
									<span style="display: block;" @click="editingImplementationFunc(data);">Modifier</span>
								</el-dropdown-item>
								<el-dropdown-item>
									<el-popover placement="top" width="220">
										<p class="el-popover__text">Voulez-vous vraiment supprimer le projet <strong>{{ data.name }}</strong> ?</p>
										<div class="el-popover__actions">
											<el-button size="mini" type="text" onclick="$('.el-popover').css('display', 'none');">Annuler</el-button>
											<el-button onclick="$('.el-popover').css('display', 'none');" @click="removeImplementation" type="danger" size="mini">Retirer</el-button>
										</div>
										<span slot="reference" style="color: red;" @click="removingGivenImplementation(data)">Supprimer</span>
									</el-popover>
								</el-dropdown-item>
								
								
							</el-dropdown-menu>
						</el-dropdown>
					</div>
				</div>
			</el-col>
		</el-row>
		
		
		
		<!-- Dialog Add Project -->
		<el-dialog
						title="Ajouter un projet"
						:visible.sync="dialogAddImplementation">
			<el-form label-width="150px" :model="addImplementation" label-position="left"  :lock-scroll="true">
				<el-row>
					<!-- Selectionner un projet -->
					<el-form-item label="Selectionnez un projet" required>
						<el-select style="width: 100%" @input="getSelectedProject" v-model="addImplementation.project_id" placeholder="Projets de l’épreuve">
							<el-option
											v-for="project in projects"
											:key="project.id"
											:label="project.name"
											:value="project.id">
							</el-option>
						</el-select>
					</el-form-item>
					<!-- URL du projet -->
					<el-form-item label="URL du projet" >
						<el-input v-model="addImplementation.url_project"  :error="addImplementation.errors.get('url_project')"></el-input>
					</el-form-item>
					<!-- URL du repo -->
					<el-form-item label="URL du repository" >
						<el-input v-model="addImplementation.url_repo"  :error="addImplementation.errors.get('url_repo')"></el-input>
					</el-form-item>
					<!-- Pondération -->
					<el-form-item label="Pondération" required :error="addImplementation.errors.get('ponderation')">
						<el-input-number style="width: 100%;" :value="selectedProject.ponderation_default" v-model="addImplementation.ponderation" controls-position="right" :step="10" :min="0" :max="100"></el-input-number>
					</el-form-item>
				</el-row>
				
				<el-button @click="dialogAddImplementation = false">Annuler</el-button>
				<el-button type="warning" :loading="addImplementation.busy" @click="storeImplementation">
					<span v-if="addImplementation.busy">Ajout en cours…</span>
					<span v-else>Ajouter</span>
				</el-button>
				
			</el-form>
		</el-dialog>
		
		<!-- Dialog Edit Project -->
		<el-dialog
						title="Ajouter un projet"
						:visible.sync="dialogEditImplementation">
			<el-form label-width="150px" :model="editImplementationForm" label-position="left"  :lock-scroll="true">
				<el-row>
					<!-- Selectionner un projet -->
					<el-form-item label="Selectionnez un projet" required :error="editImplementationForm.errors.get('project_id')">
						<el-select style="width: 100%" @input="getSelectedProject" v-model="editImplementationForm.project_id" placeholder="Projets de l’épreuve">
							<el-option
											v-for="project in projects"
											:key="project.id"
											:label="project.name"
											:value="project.id">
							</el-option>
						</el-select>
					</el-form-item>
					<!-- URL du projet -->
					<el-form-item label="URL du projet" :error="editImplementationForm.errors.get('url_project')">
						<el-input v-model="editImplementationForm.url_project"></el-input>
					</el-form-item>
					<!-- URL du repo -->
					<el-form-item label="URL du repository"  :error="editImplementationForm.errors.get('url_repo')">
						<el-input v-model="editImplementationForm.url_repo" ></el-input>
					</el-form-item>
					<!-- Pondération -->
					<el-form-item label="Pondération" required :error="editImplementationForm.errors.get('ponderation')">
						<el-input-number style="width: 100%;" :value="selectedProject.ponderation_default" v-model="editImplementationForm.ponderation" controls-position="right" :step="10" :min="0" :max="100"></el-input-number>
					</el-form-item>
				</el-row>
				
				<el-button @click="dialogEditImplementation = false">Annuler</el-button>
				<el-button type="warning" :loading="editImplementationForm.busy" @click="editImplementation">
					<span v-if="editImplementationForm.busy">Modification en cours…</span>
					<span v-else>Modifier</span>
				</el-button>
			
			</el-form>
		</el-dialog>
		
	</div>
</template>

<script>
    export default {
        data() {
            return {
              	event: [],
                dialogAddImplementation: false,
								dialogEditImplementation: false,
								addImplementation: new JiriForm({
										'project_id': '',
                    'url_project': '',
										'url_repo': '',
										'ponderation': '',
                    'student_id': this.$route.params.student,
                    'event_id': this.$route.params.id
								}),
								getImplementationsData: [],
								projects: [],
								selectedProject: [],
								getStudentData: [],
              	editingImplementation: new JiriForm({
                  'project_id': '',
                  'url_project': '',
                  'url_repo': '',
                  'ponderation': '',
                  'student_id': this.$route.params.student,
                  'event_id': this.$route.params.id
								}),
								editImplementationForm: new JiriForm({
									'project_id': '',
									'url_project': '',
									'url_repo': '',
									'ponderation': '',
									'student_id': this.$route.params.student,
									'event_id': this.$route.params.id
								}),
								loading: false,
              	removingImplementation: new JiriForm({}),
								removeImplementationForm: new JiriForm({})
						}
				},
				
				mounted() {
          var self = this;
          
          this.getEvent();
          this.getProjects();
          this.getStudent();
          this.getImplementations();
          
          this.$on('updateImplementations', () => {
              self.getImplementations();
					})
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
						
						getSelectedProject() {
                axios.get(`/api/events/${this.$route.params.id}/projects/get/${this.addImplementation.project_id}`)
                    .then( response => {
                        this.selectedProject = response.data;
                        this.addImplementation.ponderation = this.selectedProject.ponderation_default
                    } )
                    .catch( error => {
                        console.log( error );
                    } )
                    .finally( () => this.loading = false );
						},
						
						// Get implementations for current student
						getImplementations() {
                axios.get(`/api/events/${this.$route.params.id}/implementations/students/${this.$route.params.student}`)
                    .then( response => {
                        this.getImplementationsData = response.data;
                    } )
                    .catch( error => {
                        console.log( error );
                    } )
                    .finally( () => this.loading = false );
						},
						
						// Get current student
						getStudent() {
                axios.get(`/api/students/getStudent/${this.$route.params.student}`)
                    .then( response => {
                        this.getStudentData = response.data;
                    } )
                    .catch( error => {
                        console.log( error );
                    } )
                    .finally( () => this.loading = false );
						},
						
						// Store a new implementation in database
						storeImplementation() {
                Jiri.post( `/api/events/${this.$route.params.id}/implementations`, this.addImplementation )
                    .then( response => {
                        this.$emit( 'updateImplementations' );
                        this.dialogAddImplementation = false;
                        // Reset form
                        this.addImplementation.project_id = '';
                        this.addImplementation.url_project = '';
                        this.addImplementation.url_repo = '';
                        this.addImplementation.ponderation = '';

                    } ).catch( error => {
                    console.log( error );
                } );
						},

          editingImplementationFunc(project) {
              this.editingImplementation = project;
              this.editImplementationForm.project_id = '';
              this.editImplementationForm.url_project = project.url_project;
              this.editImplementationForm.url_repo = project.url_repo;
              this.editImplementationForm.ponderation = project.ponderation;
							this.dialogEditImplementation = true;
					},
					
					editImplementation() {
            Jiri.put(`/api/events/${this.$route.params.id}/implementations/${this.editingImplementation.id}`, this.editImplementationForm)
                .then( response => {
                  this.$emit('updateImplementations');
                  this.editImplementationForm.project_id = '';
                  this.editImplementationForm.url_project = '';
                  this.editImplementationForm.url_repo = '';
                  this.editImplementationForm.ponderation = '';
                  this.dialogEditImplementation = false;
                }).catch( error => {
              console.log( error )
            } )
					},
					
					removingGivenImplementation(project) {
            this.removingImplementation = project;
					},
					
					removeImplementation() {
            Jiri.delete(`/api/events/${this.$route.params.id}/implementations/${this.removingImplementation.id}`, this.removeImplementationForm)
                .then(() => {
                  this.$emit('updateImplementations');
                  this.$message({
                    message: `Le projet a été correctement retiré.`,
                    type: 'success'
                  });
                });
					}
				}
    }
</script>

<style scoped>

</style>
