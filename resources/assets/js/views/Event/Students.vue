<template>
	<div>
		<div class="top-form grid grid-sb">
			
			<el-form :inline="true" :model="addStudentForm">
				<el-form-item  :error="addStudentForm.errors.get('member_id')">
					<el-select
									v-model="addStudentForm.member_id"
									filterable
									remote
									clearable
									placeholder="Chercher un étudiant"
									:remote-method="getMembersOfJury"
									:loading="loading"
									style="width: 100%;">
						<el-option
										v-for="item in studentsResults"
										:label="item.name"
										:value="item.id"
										:key="item.id">
							<span>{{ item.name }}</span>
						</el-option>
					</el-select>
				</el-form-item>
				<el-form-item>
					<el-button type="primary"  :loading="addStudentForm.busy" @click.prevent="store">Ajouter un étudiant au jury</el-button>
				</el-form-item>
			</el-form>
			
			<el-button icon="el-icon-refresh" @click="refreshBtn">Rafraichir</el-button>
			
		</div>
		
		<div class="listing mt20" v-loading="this.loading">
			<div class="listing__item grid" v-for="user in studentsList" :key="user.id">
				<div class="col--10">
					<avatar v-bind:username="user.name" :size="36" class="listing__avatar col--10"></avatar>
				</div>
				<div class="t20 col--20">{{ user.name }}</div>
				<div class="col--50">
					<div class="points">
						<div class="point" v-for="implementation in user.implementations" :key="implementation.id">
							<span class="point__title">{{ implementation.project.name_short }}</span>
							<span class="point__point">
								<span v-if="calcAverage(implementation) < 10" class="danger">{{ calcAverage(implementation) }}</span>
								<span v-else-if="calcAverage(implementation) <= 14" class="warning">{{ calcAverage(implementation) }}</span>
								<span v-else-if="calcAverage(implementation) > 14" class="success">{{ calcAverage(implementation) }}</span>
								<span v-else>??</span>
							</span>
						</div>
					</div>
				</div>
				
				<div class="col--05 count-jury">
					<span class="danger" v-if="calcNumberOfUsers(user) == 0">{{ calcNumberOfUsers(user) }}</span>
					<span class="warning" v-else-if="calcNumberOfUsers(user) < 4">{{ calcNumberOfUsers(user) }}</span>
					<span class="success" v-else>{{ calcNumberOfUsers(user) }}</span>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" width="22" height="22">
						<g fill="#606266" fill-rule="nonzero">
							<path d="M14.7 4.91a.61.61 0 0 1 1.14.47l-1.7 4.12A.61.61 0 1 1 13 9.04l1.7-4.13z"/>
							<path d="M17.72 4.28a.61.61 0 0 1 0-1.22h.5c1.08 0 1.95.87 1.95 1.94v3.23c0 .85-.7 1.55-1.56 1.55h-2.4c-.85 0-1.54-.7-1.54-1.55V4.89a.61.61 0 1 1 1.22 0v3.34c0 .18.15.33.33.33h2.4c.18 0 .32-.15.32-.33V5c0-.4-.32-.72-.72-.72h-.5z"/>
							<path d="M17.45.55a.61.61 0 1 1 0 1.22.74.74 0 0 0 0 1.48.61.61 0 1 1 0 1.22 1.96 1.96 0 1 1 0-3.92zM18.79 4.87a.61.61 0 1 1 1.14-.44l1.77 4.62a.61.61 0 0 1-1.14.44l-1.77-4.62zM2.02 4.91a.61.61 0 1 1 1.13.47L1.44 9.5A.61.61 0 1 1 .3 9.04L2.02 4.9z"/>
							<path d="M4.89 4.28a.61.61 0 0 1 0-1.22h.5c1.07 0 1.94.87 1.94 1.94v3.23a1.55 1.55 0 0 1-1.55 1.55h-2.4c-.85 0-1.55-.7-1.55-1.55V4.89a.61.61 0 0 1 1.23 0v3.34c0 .18.14.33.33.33h2.4a.33.33 0 0 0 .32-.33V5c0-.4-.32-.72-.72-.72h-.5z"/>
							<path d="M4.76.55a.61.61 0 0 1 0 1.22.74.74 0 0 0 0 1.48.61.61 0 0 1 0 1.22 1.96 1.96 0 1 1 0-3.92zM6.1 4.87a.61.61 0 0 1 1.14-.44L9 9.05a.61.61 0 1 1-1.14.44L6.1 4.87zM8.59 16.17a.61.61 0 1 1 1.13.47L8 20.77a.61.61 0 0 1-1.13-.46l1.71-4.14z"/>
							<path d="M11.61 15.89a.61.61 0 0 1 0-1.22h.5c1.08 0 1.95.87 1.95 1.94v3.23c0 .85-.7 1.55-1.56 1.55h-2.4c-.85 0-1.54-.7-1.54-1.55V16.5a.61.61 0 0 1 1.22 0v3.34c0 .18.15.33.33.33h2.4c.18 0 .32-.15.32-.33V16.6c0-.4-.32-.72-.72-.72h-.5z"/>
							<path d="M11.33 11.81a.61.61 0 1 1 0 1.22.74.74 0 0 0 0 1.48.61.61 0 0 1 0 1.23 1.96 1.96 0 0 1 0-3.93zM12.67 16.13a.61.61 0 1 1 1.14-.43l1.76 4.62a.61.61 0 0 1-1.14.44l-1.76-4.63zM7.46 16.34a.61.61 0 1 1-.65 1.04 7.94 7.94 0 0 1-3.5-4.9.61.61 0 0 1 1.18-.28 6.72 6.72 0 0 0 2.97 4.14zM17.63 11.8a.61.61 0 0 1 1.2.2 7.94 7.94 0 0 1-2.9 4.89.61.61 0 0 1-.75-.96 6.72 6.72 0 0 0 2.45-4.14zM9.04 4.25a.61.61 0 0 1-.36-1.17 7.94 7.94 0 0 1 5.15.16.61.61 0 0 1-.43 1.15 6.72 6.72 0 0 0-4.36-.14z"/>
						</g>
					</svg>
				</div>
				
				<div class="col--05" style="text-align: right;">
					<span class="scorePond danger" v-if="calcScoreGlobal(user) < 10">{{ calcScoreGlobal(user) }}</span>
					<span class="scorePond warning" v-else-if="calcScoreGlobal(user) <= 14">{{ calcScoreGlobal(user) }}</span>
					<span class="scorePond success" v-else>{{ calcScoreGlobal(user) }}</span>
				
				</div>
				
				<div class="col--05" style="text-align: right;">
					
					<el-dropdown trigger="click">
						<span class="el-dropdown-link">
							<i class="el-icon-more el-icon--right" style="cursor: pointer"></i>
						</span>
						<el-dropdown-menu slot="dropdown">
							<router-link class="el-dropdown-menu__item" :to="'/events/' + $route.params.id + '/students/' + user.id + '/config'">Configurer</router-link>
							<router-link class="el-dropdown-menu__item" :to="'/events/' + $route.params.id + '/students/' + user.id">Noter les projets</router-link>
							
							<el-dropdown-item>
								<span v-if="user.performance == null" @click="storingCoteGlobalFunc(user);" style="display: block;">Attribuer cote global</span>
								<span style="display: block;" @click="editingCoteGlobalFunc(user);"  v-else>Modifier la cote globale</span>
							</el-dropdown-item>
							
							<el-dropdown-item>
								<el-popover placement="top" width="220">
									<p class="el-popover__text">Voulez-vous vraiment retirer <strong>{{ user.name }}</strong> du jury ?</p>
									<div class="el-popover__actions">
										<el-button size="mini" type="text" onclick="$('.el-popover').css('display', 'none');">Annuler</el-button>
										<el-button onclick="$('.el-popover').css('display', 'none');" @click="removeStudent" type="danger" size="mini">Retirer</el-button>
									</div>
									<span slot="reference" style="color: red;" @click="removingGivenStudent(user)">Supprimer</span>
								</el-popover>
							</el-dropdown-item>
						</el-dropdown-menu>
					</el-dropdown>
					
				</div>
			
			</div>
		</div>
		
		<!-- Store cote global -->
		<el-dialog :visible.sync="dialogCoteGlobale" :lock-scroll="true" title="Attribuer la cote globale">
			<el-form :model="storeCoteGlobalForm">
				<el-form-item label="Score calculé" :error="storeCoteGlobalForm.errors.get('calculated_score')">
					<el-input :disabled="true" v-model="storeCoteGlobalForm.calculated_score" placeholder="Score calculé"></el-input>
				</el-form-item>
				<el-form-item label="Score manuel" :error="storeCoteGlobalForm.errors.get('manual_score')">
					<el-input v-model="storeCoteGlobalForm.manual_score" :value="storeCoteGlobalForm.manual_score" placeholder="Score manuel"></el-input>
				</el-form-item>
				<el-form-item>
					<el-button type="primary" :loading="storeCoteGlobalForm.busy" @click.prevent="storeCoteGlobal">Ajouter une cote manuelle</el-button>
				</el-form-item>
			</el-form>
		</el-dialog>
		
		<!-- Edit cote global -->
		<el-dialog :visible.sync="dialogEditCoteGlobale" :lock-scroll="true" title="Modifier la cote globale">
			<el-form :model="editCoteGlobalForm">
				<el-form-item label="Score calculé" :error="editCoteGlobalForm.errors.get('calculated_score')">
					<el-input :disabled="true" v-model="editCoteGlobalForm.calculated_score" placeholder="Score calculé"></el-input>
				</el-form-item>
				<el-form-item label="Score manuel" :error="editCoteGlobalForm.errors.get('manual_score')">
					<el-input v-model="editCoteGlobalForm.manual_score" :value="editCoteGlobalForm.manual_score" placeholder="Score manuel"></el-input>
				</el-form-item>
				<el-form-item>
					<el-button type="primary" :loading="editCoteGlobalForm.busy" @click.prevent="updateCoteGlobal">Modifier la cote manuelle</el-button>
				</el-form-item>
			</el-form>
		</el-dialog>
	
	</div>
</template>

<script>
    import Avatar from 'vue-avatar'
    export default {
        data() {
            return {
                event: [],
								dialogCoteGlobale: false,
								dialogEditCoteGlobale: false,
								storingCoteGlobal: new JiriForm({
                    calculated_score: '',
                    manual_score: '',
                    student_id: '',
								}),
								editingCoteGlobal: new JiriForm({
									calculated_score: '',
									manual_score: '',
									student_id: ''
								}),
                loading: false,
                addStudentForm: new JiriForm({
                    member_id: ''
                }),
                studentsResults: [],
                studentsList: [],
                removingStudent: new JiriForm({  }),
                removeStudentForm: new JiriForm({
                    loading: false
                }),
								storeCoteGlobalForm: new JiriForm({
										calculated_score: '',
                    manual_score: '',
                    student_id: '',
                    event_id: this.$route.params.id
								}),
								editCoteGlobalForm: new JiriForm({
									calculated_score: '',
									manual_score: '',
									student_id: '',
									event_id: this.$route.params.id,
								})
            }
        },

        mounted() {
            var self = this;

            this.getEvent();
            this.getStudentsList();

            this.$on( 'updateListStudents', () => {
                self.getStudentsList();
            } );
					
        },

        methods: {
          	refreshBtn() {
          	  this.$emit('updateListStudents');
						},
					
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

            getMembersOfJury(searchstudents) {
                if ( searchstudents.length > 2 ) {
                    this.loading = true;
                    setTimeout(() => {
                        this.loading = false;
                        axios.get('/api/jiri/searchstudents', {params: {searchstudents: searchstudents}})
                            .then(response => {
                                this.studentsResults = response.data;
                            });
                    }, 300);
                }
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

            store() {
                Jiri.post(`/api/events/${this.$route.params.id}/students/add`, this.addStudentForm)
                    .then( response => {
                        this.$emit('updateListStudents');
                        this.addStudentForm.member_id = '';
                    }).catch( error => {
                    console.log( error )
                } )
            },
						
						storeCoteGlobal() {
              Jiri.post(`/api/events/${this.$route.params.id}/students/performance`, this.storeCoteGlobalForm)
                  .then( response => {
                      this.$emit('updateListStudents');
                      this.storeCoteGlobalForm.manual_score = '';
                      this.storeCoteGlobalForm.calculated_score = '';
                      this.storeCoteGlobalForm.student_id = '';
                      this.dialogCoteGlobale = false;
                  }).catch( error => {
                  console.log( error )
              } )
						},
	
						updateCoteGlobal() {
							Jiri.put(`/api/events/${this.$route.params.id}/students/performance/${this.editingCoteGlobal.id}`, this.editCoteGlobalForm)
									.then( response => {
										this.$emit('updateListStudents');
										this.editCoteGlobalForm.manual_score = '';
										this.editCoteGlobalForm.calculated_score = '';
										this.editCoteGlobalForm.student_id = '';
										this.dialogEditCoteGlobale = false;
									}).catch( error => {
								console.log( error )
							} )
						},

            storingCoteGlobalFunc(user) {
                this.storingCoteGlobal = user;
                this.storeCoteGlobalForm.calculated_score = user.scoreCalculated;
                this.storeCoteGlobalForm.student_id = user.id;
                this.dialogCoteGlobale = true;
            },

          editingCoteGlobalFunc(user) {
            this.editingCoteGlobal = user;
            this.editCoteGlobalForm.calculated_score = user.scoreCalculated;
            this.editCoteGlobalForm.manual_score = user.performance.manual_score;
            this.editCoteGlobalForm.student_id = user.id;
            this.dialogEditCoteGlobale = true;
          },

            removingGivenStudent(user) {
                this.removingStudent = user;
            },

            removeStudent() {
                Jiri.delete(`/api/events/${this.$route.params.id}/students/remove/${this.removingStudent.id}`, this.removeStudentForm)
                    .then(() => {
                        this.$emit('updateListStudents');
                        this.$message({
                            message: `Le membre (${this.deletingStudent.name}) a été correctement retiré.`,
                            type: 'success'
                        });
                    });
            },
					
					calcAverage: function (subItem) {
		 
						let total = 0,
								length = subItem.scores.length;
						
						for (var i = 0; i < length; i++) {
							total += parseFloat(subItem.scores[i].score);
						}
						
						subItem.avg_score = total / length;
						
						return Math.round(subItem.avg_score * 100) / 100
					},
					

          calcNumberOfUsers: function (subItem) {
            subItem.storeCount = [];
						
            for(var i = 0; i < subItem.implementations.length; i++){
              //create a dummy variable scoreCount
              if(subItem.implementations[i].scores){
                subItem.storeCount.push(subItem.implementations[i].scores.length);
              }
            }

              if (typeof subItem.storeCount !== 'undefined' && subItem.storeCount.length > 0) {
                  return Math.max.apply(Math, subItem.storeCount);
              } else {
                return '0'
							}
          },
					
					calcScoreGlobal: function (subItem) {
						subItem.allAvgData = [];
						
						for(var i = 0; i < subItem.implementations.length; i++) {
						  if (subItem.implementations[i].avg_score) {
						    
                subItem.allAvgData.push({
                  scoreAvg: subItem.implementations[i].avg_score,
                  scorePonderation: subItem.implementations[i].ponderation,
                });
							}
						}
						
						subItem.allAvgResultsWithPonderation = [];
						for (var i = 0; i < subItem.allAvgData.length; i++) {
						  subItem.allAvgResultsWithPonderation.push( subItem.allAvgData[i].scoreAvg * (subItem.allAvgData[i].scorePonderation/100) );
						}
						
						let total = subItem.allAvgResultsWithPonderation.reduce((a, b) => a + b, 0);
						
						subItem.scoreCalculated = Math.round(total * 100) / 100;
						
						console.log();
						
						if( subItem.performance !== null ) {
						  if (subItem.performance.manual_score !== '') {
						    return subItem.performance.manual_score;
							} else {
                return subItem.scoreCalculated;
							}
						} else {
              return subItem.scoreCalculated;
						}
						
						
						
          }
					
        },

        components: {
            Avatar
        }
    }
</script>
