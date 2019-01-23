<template>
	<div>
		<div class="grid grid-sb" style="margin-bottom: 30px">
			<span class="page-subtitle">Liste des projets de {{ studentData.name }}</span>
		</div>
		
		<el-row :gutter="20">
			<el-col v-for="data in getImplementationsData" style="margin-bottom: 20px;" class="listing__item listing__item--box">
				<div class="grid-content box-wrapper">
					<h2 role="heading" aria-level="2" class="box-implementation-title">{{ data.project.name }}</h2>
					<p class="box-implementation-description">{{ data.project.description }}</p>
					<span class="box-implementation-subtitle">URL du projet</span>
					<a href="" target="_blank" class="box-implementation-link">{{ data.url_project }}</a>
					<span class="box-implementation-subtitle">Repository</span>
					<a href="" target="_blank" class="box-implementation-link">{{ data.url_repo }}</a>
					<span class="box-implementation-subtitle">Cote attribuée</span>
					<span class="box-implementation-link box-implementation-link--red" v-if="data.score == null">Cote manquante</span>
					<span class="box-implementation-link" v-else>{{ data.score.score}}/20</span>
					<span class="box-implementation-subtitle">Commentaire</span>
					<span class="box-implementation-link box-implementation-link--red" v-if="data.score == null">Commentaire manquant</span>
					<p class="box-implementation-description" v-else>{{ data.score.comment }}</p>
					<div class="button-top-right">
						<el-button type="primary" @click="addingScore(data)" plain icon="el-icon-plus" v-if="data.score == null">Attribuer une cote</el-button>
						<el-button type="warning" @click="editingScore(data)" plain icon="el-icon-edit" v-else>Éditer</el-button>
					</div>
				</div>
			</el-col>
		</el-row>
		
		
		<!-- ADD SCORE DIALOG -->
		<el-dialog :visible.sync="addScoreDialog" :lock-scroll="true" title="Ajouter une notation">
			<el-form :model="addScore" label-position="left" label-width="100px">
				<el-row>
					<el-form-item label="Note (sur 20)" required :error="addScore.errors.get('score')">
						<el-input-number style="width: 100%" v-model="addScore.score" controls-position="right" :min="0" :max="20"></el-input-number>
					</el-form-item>
					<el-form-item label="Commentaire" :error="addScore.errors.get('comment')">
						<el-input type="textarea" :rows="2" style="width: 100%" v-model="addScore.comment"></el-input>
					</el-form-item>
				</el-row>
				
				<el-button @click="addScoreDialog = false">Annuler</el-button>
				<el-button type="warning" :loading="addScore.busy" @click="storeScore">
					<span v-if="addScore.busy">Notation en cours…</span>
					<span v-else>Noter</span>
				</el-button>
				
			</el-form>
		</el-dialog>
		
		<!-- ADD SCORE DIALOG -->
		<el-dialog :visible.sync="editScoreDialog" :lock-scroll="true" title="Ajouter une notation">
			<el-form :model="editScore" label-position="left" label-width="100px">
				<el-row>
					<el-form-item label="Note (sur 20)" required :error="editScore.errors.get('score')">
						<el-input-number style="width: 100%" v-model="editScore.score" controls-position="right" :min="0" :max="20"></el-input-number>
					</el-form-item>
					<el-form-item label="Commentaire" :error="editScore.errors.get('comment')">
						<el-input type="textarea" :rows="2" style="width: 100%" v-model="editScore.comment"></el-input>
					</el-form-item>
				</el-row>
				
				<el-button @click="editScoreDialog = false">Annuler</el-button>
				<el-button type="warning" :loading="editScore.busy" @click="editScoreFunc">
					<span v-if="editScore.busy">Notation en cours…</span>
					<span v-else>Noter</span>
				</el-button>
			
			</el-form>
		</el-dialog>
		
	</div>
</template>

<script>
    import Avatar from 'vue-avatar'
    export default {
        data() {
            return {
							loading: true,
            	studentData: [],
							getImplementationsData: [],
							addScoreDialog: false,
							editScoreDialog: false,
							addScore: new JiriForm({
									user_id: Jiri.currentUser.id,
									implementation_id: '',
									score: '',
									comment: ''
							}),
              editScore: new JiriForm({
                user_id: Jiri.currentUser.id,
                implementation_id: '',
                score: '',
                comment: ''
              }),
							addingScoreData: new JiriForm({}),
							editingScoreData: new JiriForm({}),
						}
				},
				
				mounted() {
            var self = this;
            
            this.getStudentData();
            this.getImplementations();

            this.$on('updateImplementations', () => {
                self.getImplementations();
            })
					
						this.fetchDataInterval();
				},
				
				methods: {
					
            getStudentData() {
                this.loading = true;
                axios.get(`/api/students/getStudent/${this.$route.params.student}`)
                    .then(response => {
                        this.studentData = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => this.loading = false);

            },

            // Get implementations for current student
            getImplementations() {
                axios.get(`/api/events/${this.$route.params.id}/implementations/students/score/${this.$route.params.student}`)
                    .then( response => {
                        this.getImplementationsData = response.data;
                    } )
                    .catch( error => {
                        console.log( error );
                    } )
                    .finally( () => this.loading = false );
            },
						
						addingScore(implementation) {
                this.addingScoreData = implementation;
                this.addScore.implementation_id = this.addingScoreData.id;
                this.addScoreDialog = true;
						},

						editingScore(implementation) {
							this.editingScoreData = implementation;
							this.editScore.implementation_id = this.editingScoreData.id;
							this.editScore.score = this.editingScoreData.score.score;
							this.editScore.comment = this.editingScoreData.score.comment;
							this.editScoreDialog = true;
						},
						
						storeScore() {
                Jiri.post( '/api/scores', this.addScore )
                    .then( response => {
                        this.$emit( 'updateImplementations' );
                        this.addScoreDialog = false;

                        // Reset form
                        this.addScore.score = '';
                        this.addScore.comment = '';
                        this.addScore.implementation_id = '';

                    } ).catch( error => {
                    console.log( error );
                } );
						},
					
						editScoreFunc() {
              Jiri.put(`/api/scores/${this.editingScoreData.score.id}`, this.editScore)
                  .then( response => {
                    this.$emit('updateImplementations');
                    this.editScore.implementation_id = '';
                    this.editScore.score = '';
                    this.editScore.comment = '';
                    this.editScoreDialog = false;
                  }).catch( error => {
                console.log( error )
              } )
						}
				},
				
				components: {
            Avatar
				}
    }
</script>

<style scoped>

</style>
