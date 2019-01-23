<template>
    <div>
        <div class="top-form grid grid-sb">

            <el-form :inline="true" :model="createStudent">
                <el-form-item :error="createStudent.errors.get('name')">
                    <el-input v-model="createStudent.name" placeholder="Nom"></el-input>
                </el-form-item>
                <el-form-item :error="createStudent.errors.get('email')">
                    <el-input v-model="createStudent.email" class="input-email" type="email"
                              placeholder="Adresse e-mail"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" :loading="createStudent.busy" @click.prevent="store">Ajouter un étudiant
                    </el-button>
                </el-form-item>
            </el-form>

            <div class="col--25 ">
                <el-input
                        placeholder="Chercher un étudiant"
                        suffix-icon="el-icon-search"
                        clearable
                        @input="searchStudents"
                        v-model="keywords">
                </el-input>
            </div>

        </div>

        <div class="listing mt20" v-loading="this.loading">
            <div class="listing__item grid" v-for="student in students.data">
                <div class="col--10">
                    <avatar v-bind:username="student.name" :size="36" class="listing__avatar col--10"></avatar>
                </div>
                <div class="listing__name t20 col--30">{{ student.name }}</div>
                <div class=" listing__name t20 col--40">{{ student.email }}</div>

                <div class="listing__button col--20">
                    <el-button icon="el-icon-edit" size="small" @click="editStudent(student)"></el-button>


                    <el-popover placement="top" width="220">
                        <p class="el-popover__text">Voulez-vous vraiment supprimer <strong>{{ deletingStudent.name
                            }}</strong> ?</p>
                        <div class="el-popover__actions">
                            <el-button size="mini" type="text" onclick="$('.el-popover').css('display', 'none');">
                                Annuler
                            </el-button>
                            <el-button onclick="$('.el-popover').css('display', 'none');" @click="destroyStudent"
                                       type="danger" size="mini">Supprimer
                            </el-button>
                        </div>
                        <el-button slot="reference" @click="deleteGivenStudent(student)" type="danger" size="small"
                                   plain icon="el-icon-delete"></el-button>
                    </el-popover>


                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <el-pagination
                    prop="animals"
                    v-if="students.total > 15"
                    layout="prev, pager, next"
                    :disabled="this.loading"
                    :page-size="students.per_page"
                    @current-change="handleCurrentChange"
                    :current-page="students.current_page"
                    :total="students.total">
            </el-pagination>
        </div>

        <!-- Update a student -->
        <el-dialog :visible.sync="updateStudent.dialogUpdateStudent" :lock-scroll="true" title="Modifier un étudiant">
            <el-form :model="updateStudent" class="el-dialog__width" label-position="left" label-width="70px">
                <el-row>
                    <el-form-item label="Nom" required :error="updateStudent.errors.get('name')">
                        <el-input v-model="updateStudent.name" name="name" id="name"></el-input>
                    </el-form-item>
                    <el-form-item label="E-mail" required :error="updateStudent.errors.get('email')">
                        <el-input v-model="updateStudent.email" name="email" id="email"></el-input>
                    </el-form-item>
                    <el-form-item label="Statut" required :error="updateStudent.errors.get('is_active')">
                        <el-switch v-model="updateStudent.is_active" active-text="Actif" inactive-text="Inactif"
                                   :active-value="1" :inactive-value="0">
                        </el-switch>
                    </el-form-item>
                </el-row>

                <el-button @click="updateStudent.dialogUpdateStudent = false">Annuler</el-button>
                <el-button type="warning" :loading="updateStudent.busy" @click="update">
                    <span v-if="updateStudent.busy">Mise à jour en cours…</span>
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
                createStudent: new JiriForm({
                    name: '',
                    email: ''
                }),
                updateStudent: new JiriForm({
                    name: '',
                    email: '',
                    is_active: '',
                    dialogUpdateStudent: false
                }),
                deleteStudent: new JiriForm({
                    popoverDeleteStudent: false,
                }),
                loading: true,
                errored: false,
                updatingStudent: new JiriForm({name: null}),
                deletingStudent: new JiriForm({name: null}),
                keywords: '',
                students: []
            }
        },

        directives: {debounce},

        mounted() {
            var self = this;

            this.getStudents();
            this.$on('updateListStudents', () => {
                self.getStudents();
            });
        },

        methods: {
            /*
             * Get list of students
             */
            getStudents() {
                this.loading = true;

                axios.get('/api/students', {params: {keywords: this.keywords}})
                    .then(response => {
                        this.students = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => this.loading = false);
            },

            /*
             * Edit the given student
             */
            editStudent(student) {
                this.updatingStudent = student;
                this.updateStudent.name = student.name;
                this.updateStudent.email = student.email;
                this.updateStudent.is_active = student.is_active;
                this.updateStudent.dialogUpdateStudent = true;
            },

            /*
             * Search a student
             */
            searchStudents: debounce(function () {
                this.getStudents();
            }, 200),

            /*
             * Store a new student in database
             */
            store() {
                Jiri.post('api/students', this.createStudent)
                    .then(response => {
                        this.$emit('updateListStudents');

                        // Reset form
                        this.createStudent.name = '';
                        this.createStudent.email = '';

                    }).catch(error => {
                    console.log(error);
                });
            },

            /*
            * Update data's of student
            */
            update() {
                Jiri.put(`api/students/${this.updatingStudent.id}`, this.updateStudent)
                    .then(response => {
                        this.$emit('updateListStudents');
                        this.updateStudent.dialogUpdateStudent = false;
                        this.$message({
                            message: `L’étudiant (${this.updatingStudent.name}) a été mis à jour.`,
                            type: 'success'
                        });
                    });
            },

            /*
            * Delete the given type
            */
            deleteGivenStudent(student) {
                this.deletingStudent = student;
                this.deleteStudent.popoverDeleteStudent = true;
            },

            /**
             * Delete the current type
             */
            destroyStudent() {
                Jiri.delete(`api/students/${this.deletingStudent.id}`, this.deleteStudent)
                    .then(() => {
                        this.$emit('updateListStudents');
                        this.$message({
                            message: `L’étudiant (${this.deletingStudent.name}) a été correctement supprimé.`,
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
                axios.get(`api/students?page=${val}&keywords=${this.keywords}`)
                    .then(response => {
                        this.students = response.data
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
