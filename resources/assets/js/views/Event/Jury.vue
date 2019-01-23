<template>
	<div>
		<div class="top-form grid grid-sb">
			
			<el-form :inline="true" :model="addMemberOfJuryForm">
				<el-form-item  :error="addMemberOfJuryForm.errors.get('member_id')">
					<el-select
									v-model="addMemberOfJuryForm.member_id"
									filterable
									remote
									clearable
									placeholder="Chercher un juré"
									:remote-method="getMembersOfJury"
									:loading="loading"
									style="width: 100%;">
						<el-option
										v-for="item in membersOfJuryResults"
										:label="item.name"
										:value="item.id"
										:key="item.id">
							<span>{{ item.name }}</span>
						</el-option>
					</el-select>
				</el-form-item>
				<el-form-item>
					<el-button type="primary"  :loading="addMemberOfJuryForm.busy" @click.prevent="store">Ajouter un membre au jury</el-button>
				</el-form-item>
			</el-form>
		</div>
		
		<div class="listing mt20" v-loading="this.loading">
			<div class="listing__item grid" v-for="user in membersOfJuryList">
				<div class="col--10">
					<avatar v-bind:username="user.name" :size="36" class="listing__avatar col--10"></avatar>
				</div>
				<div class="listing__name t20 col--20">{{ user.name }}</div>
				<div class="listing__name t20 col--30">{{ user.email }}</div>
				<div class="listing__name t20 col--30">{{ user.company }}</div>
				
				<div class="listing__button col--10">
					<el-popover placement="top" width="220">
						<p class="el-popover__text">Voulez-vous vraiment retirer <strong>{{ user.name }}</strong> du jury ?</p>
						<div class="el-popover__actions">
							<el-button size="mini" type="text" onclick="$('.el-popover').css('display', 'none');">Annuler</el-button>
							<el-button onclick="$('.el-popover').css('display', 'none');" @click="removeMember" type="danger" size="mini">Retirer</el-button>
						</div>
						<el-button slot="reference" @click="removingGivenMember(user)" type="danger" size="small" plain icon="el-icon-close"></el-button>
					</el-popover>
				</div>
				
			</div>
		</div>
		
	</div>
</template>

<script>
    import Avatar from 'vue-avatar'
    export default {
        data() {
            return {
                event: [],
                loading: false,
								addMemberOfJuryForm: new JiriForm({
										member_id: ''
								}),
								membersOfJuryResults: [],
								membersOfJuryList: [],
								removingMember: new JiriForm({  }),
								removeMemberForm: new JiriForm({
										loading: false
								})
            }
        },

        mounted() {
            var self = this;
            
            this.getEvent();
            this.getMembersOfJuryList();

            this.$on( 'updateListMembersOfJury', () => {
                self.getMembersOfJuryList();
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
						
						getMembersOfJury(searchmembersofjury) {
                if ( searchmembersofjury.length > 2 ) {
                    this.loading = true;
                    setTimeout(() => {
                        this.loading = false;
                        axios.get('/api/jiri/searchmembersofjury', {params: {searchmembersofjury: searchmembersofjury}})
                            .then(response => {
                                this.membersOfJuryResults = response.data;
                            });
                    }, 300);
								}
						},
						
						getMembersOfJuryList() {
              this.loading = true;
              axios.get(`/api/events/${this.$route.params.id}/jury/get`)
                  .then(response => {
                      this.membersOfJuryList = response.data;
                  })
                  .catch(error => {
                      console.log(error);
                      this.errored = true;
                  })
                  .finally(() => this.loading = false);
								
						},
						
						store() {
                Jiri.post(`/api/events/${this.$route.params.id}/jury/add`, this.addMemberOfJuryForm)
										.then( response => {
                        this.$emit('updateListMembersOfJury');
                        this.addMemberOfJuryForm.member_id = '';
										}).catch( error => {
										    console.log( error )
								} )
						},
						
						removingGivenMember(user) {
                this.removingMember = user;
						},
						
						removeMember() {
                Jiri.delete(`/api/events/${this.$route.params.id}/jury/remove/${this.removingMember.id}`, this.removeMemberForm)
                    .then(() => {
                        this.$emit('updateListMembersOfJury');
                        this.$message({
                            message: `Le membre (${this.deletingStudent.name}) a été correctement retiré.`,
                            type: 'success'
                        });
                    });
						},
        },

        components: {
            Avatar
        }
    }
</script>

<style scoped>

</style>
