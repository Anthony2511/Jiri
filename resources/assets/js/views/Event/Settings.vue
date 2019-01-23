<template>
	<div>
		
		
		<el-row :gutter="20">
			<el-row style="margin-bottom: 20px;">
				<el-button type="warning" icon="el-icon-setting" @click="editEvent(event)" plain>Modifier l’épreuve</el-button>
			</el-row>
			<div class="settings__bloc">
				<el-col :span="7" class="listing__item listing__item--box">
					<div class="grid-content box-wrapper">
						<h3 class="box-title">Nom de l’épreuve</h3>
						<strong class="box-content">{{ event.course_name }}</strong>
					</div>
				</el-col>
				<el-col :span="7" :offset="1" class="listing__item .listing__item--box">
					<div class="grid-content box-wrapper">
						<h3 class="box-title">Année académique</h3>
						<strong class="box-content">{{ event.academic_year }}</strong>
					</div>
				</el-col>
				<el-col :span="7" :offset="1" class="listing__item .listing__item--box">
					<div class="grid-content box-wrapper">
						<h3 class="box-title">Session</h3>
						<strong class="box-content">{{ event.exam_session | formatSession }}</strong>
					</div>
				</el-col>
				<el-col :span="7" class="listing__item listing__item--box">
					<div class="grid-content box-wrapper">
						<h3 class="box-title">Date de l’épreuve</h3>
						<strong class="box-content">{{ event.date_event | formatDate }}</strong>
					</div>
				</el-col>
				<el-col :span="7" :offset="1" class="listing__item .listing__item--box">
					<div class="grid-content box-wrapper">
						<h3 class="box-title">Statut</h3>
						<strong class="box-content">{{ event.status | formatStatus }}</strong>
					</div>
				</el-col>
			</div>
		</el-row>
		

		
		
		<!-- Update event -->
		<el-dialog :visible.sync="dialogUpdateEvent" :lock-scroll="true" title="Modifier l’événement">
			<el-form :model="updateEvent" class="dialog-simple-form" label-position="left" label-width="150px">
				<el-row>
					<el-form-item label="Nom" required :error="updateEvent.errors.get('course_name')">
						<el-input v-model="updateEvent.course_name" name="course_name" id="course_name"></el-input>
					</el-form-item>
					<!-- Année académique -->
					<el-form-item label="Année académique" required :error="updateEvent.errors.get('academic_year')">
						<el-date-picker v-model="updateEvent.academic_year"
														type="year"
														style="width: 100%;"
														format="yyyy"
														value-format="yyyy"
														placeholder="Sélectionnez une année">
						</el-date-picker>
					</el-form-item>
					<!-- Session d'examen -->
					<el-form-item label="Session d’examen" required :error="updateEvent.errors.get('exam_session')">
						<el-select style="width: 100%;" v-model="updateEvent.exam_session" placeholder="Sélectionnez une session">
							<el-option value="1">1</el-option>
							<el-option value="2">2</el-option>
							<el-option value="3">3</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="Date" required :error="updateEvent.errors.get('date_event')">
						<el-date-picker v-model="updateEvent.date_event"
														type="date"
														style="width: 100%;"
														:picker-options="{
															firstDayOfWeek: 1
														}"
														format="dd/MM/yyyy"
														value-format="yyyy-MM-dd HH:MM:SS"
														placeholder="Sélectionnez une date">
						</el-date-picker>
					</el-form-item>
						<!-- Statut -->
						<el-form-item label="Statut" required :error="updateEvent.errors.get('status')">
							<el-select style="width: 100%;" v-model="updateEvent.status" placeholder="Sélectionnez une session">
								<el-option value="0" label="En attente">En attente</el-option>
								<el-option value="1" label="En cours">En cours</el-option>
								<el-option value="2" label="Terminé">Terminé</el-option>
							</el-select>
						</el-form-item>
				</el-row>
				
				<el-button @click="dialogUpdateEvent = false">Annuler</el-button>
				<el-button type="warning" :loading="updateEvent.busy" @click="update">
					<span v-if="updateEvent.busy">Mise à jour en cours…</span>
					<span v-else>Modifier</span>
				</el-button>
			</el-form>
		</el-dialog>
		
	</div>
</template>

<script>
    import moment from 'moment';
    
    export default {
        data() {
            return {
							event: [],
							dialogUpdateEvent: false,
							updateEvent: new JiriForm({
									course_name: '',
									academic_year: '',
									exam_session: '',
									status: '',
									date_event: ''
							}),
							updatingEvent: new JiriForm({ }),
						}
				},
				
        mounted() {
            var self = this;

            this.getEvent();

            this.$on( 'updateEvent', () => {
                self.getEvent();
            } );
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
						
						editEvent(event) {
                this.updatingEvent = event;
                this.updateEvent.course_name = event.course_name;
                this.updateEvent.academic_year = event.academic_year;
                this.updateEvent.exam_session = event.exam_session;
                this.updateEvent.date_event = event.date_event
                this.updateEvent.status = event.status;
                this.dialogUpdateEvent = true;
						},
						
						update() {
                Jiri.put( `/api/events/${this.updatingEvent.id}`, this.updateEvent )
                    .then( response => {
                        this.$emit( 'updateEvent' );
                        this.dialogUpdateEvent = false;
                        this.$message({
                            message: `L’évenement (${this.updatingEvent.course_name}) a été mis à jour.`,
                            type: 'success'
                        });
                    } );
						}
				},
				
				filters: {
            formatDate: function(value) {
                if (value) {
                    return moment(String(value)).format('DD.MM.YYYY')
                }
            },

            formatSession: function (value) {
                if(value == 1) {
                    return 'Première session'
								} else if(value == 2) {
                    return 'Deuxième session'
								} else if(value == 3) {
                    return 'Troisième session'
								}
            },

            formatStatus: function (value) {
                if(value == 0) {
                    return 'En attente'
                } else if(value == 1) {
                    return 'En cours'
                } else if(value == 2) {
                    return 'Terminé'
                }
            }
				}
    }
</script>

<style scoped>

</style>
