<template>
	<div>
		<el-button type="primary" class="el-button__events" v-if="jiri.currentUser.is_admin == 1" @click="createEvent.dialogCreateEvent = true;">Ajouter une épreuve</el-button>
		
		
		<!-- (DIALOG) Ajouter une épreuve -->
		<el-dialog :visible.sync="createEvent.dialogCreateEvent" :lock-scroll="true" title="Ajouter une épreuve">
			<el-form :model="createEvent" class="el-dialog__width"  label-position="left" label-width="130px">
				
				<el-row>
					<!-- Nom du cours -->
					<el-form-item label="Nom du cours" required :error="createEvent.errors.get('course_name')">
						<el-input v-model="createEvent.course_name" name="course_name" id="course_name"></el-input>
					</el-form-item>
					<!-- Année académique -->
					<el-form-item label="Année académique" class="el-form__year" required :error="createEvent.errors.get('academic_year')">
						<el-date-picker v-model="createEvent.academic_year"
										type="year"
										style="width: 100%;"
										format="yyyy"
										value-format="yyyy"
										placeholder="Sélectionnez une année">
						</el-date-picker>
					</el-form-item>
					<!-- Session d'examen -->
					<el-form-item label="Session d’examen" required :error="createEvent.errors.get('exam_session')">
						<el-select style="width: 100%;" v-model="createEvent.exam_session" placeholder="Sélectionnez une session">
							<el-option value="1">1</el-option>
							<el-option value="2">2</el-option>
							<el-option value="3">3</el-option>
						</el-select>
					</el-form-item>
					<!-- Date de l'event -->
					<el-form-item label="Date" class="el-form__year" required :error="createEvent.errors.get('date_event')">
						<el-date-picker v-model="createEvent.date_event"
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
				</el-row>
				
				<el-button @click="createEvent.dialogCreateEvent = false">Annuler</el-button>
				<el-button type="primary" :loading="createEvent.busy" @click="store">
					<span v-if="createEvent.busy">Création en cours…</span>
					<span v-else>Créer</span>
				</el-button>
				
			</el-form>
		</el-dialog>
	
		
		<!-- LISTING -->
		<div class="listing mt20" v-loading="this.loading">
			<div class="listing__item listing__item--nopadding" v-for="event in events.data" v-if="event.status == 1">
				
				<div v-if="jiri.currentUser.is_admin == 1">
					<router-link class="listing__link grid grid--padding" :to="{ name: 'EventDashboard', params: { id: event.id, title: event.course_name } }">
					<!-- Title -->
					<div class="t20 col--20">
						<span class="listing__eventTitle">{{ event.course_name }}</span>
					</div>
					
					<!-- Year -->
					<div class="t20 col--10">
						<span class="listing__regularText">{{ event.academic_year }}</span>
					</div>
					
					<!-- Exam session -->
					<div class="t20 col--20">
						<span class="listing__regularText">{{ event.exam_session | formatSession }}</span>
					</div>
					
					<!-- Nombre d'étudiants inscrits -->
					<div class="t20 grid col--10">
						<span class="listing__regularText">{{ event.students.length }}</span>
						<svg class="listing__img" xmlns="http://www.w3.org/2000/svg" width="26" height="17" viewBox="0 0 26 17">
							<g fill="#42A5F5" fill-rule="nonzero">
								<path d="M6.26 7.44a.65.65 0 0 1-.45 1.22L1.58 7.11a1.45 1.45 0 0 1 0-2.71L12.5.33c.33-.12.68-.12 1 0L24.44 4.4a1.45 1.45 0 0 1 0 2.71l-4.24 1.54a.28.28 0 0 0-.18.27v1.61a4.59 4.59 0 0 1-2.86 4.27l-2.4.98a4.59 4.59 0 0 1-3.48 0l-2.42-.98A4.59 4.59 0 0 1 6 10.55a.65.65 0 0 1 1.3 0c0 1.34.8 2.54 2.05 3.04l2.4.99c.8.32 1.7.32 2.5 0l2.4-.99a3.29 3.29 0 0 0 2.06-3.05V8.92c0-.67.4-1.26 1.04-1.49l4.22-1.54a.15.15 0 0 0 0-.28L13.05 1.55a.15.15 0 0 0-.1 0L2.04 5.61a.15.15 0 0 0-.1.14c0 .06.04.12.1.14l4.22 1.55z"/>
								<path d="M16.92 8.4a.65.65 0 1 1 .42 1.22l-3.12 1.08c-.79.27-1.64.27-2.43 0L3.7 7.9a.65.65 0 0 1 .42-1.23l8.1 2.8c.52.17 1.07.17 1.59 0l3.12-1.08z"/>
								<path d="M2.75 11.76V9.53A3.5 3.5 0 0 1 5.7 6.08l7.2-1.13a.65.65 0 0 1 .2 1.28L5.9 7.36a2.2 2.2 0 0 0-1.85 2.17v2.56c0 .2-.1.4-.26.52l-1.1.82v1.62H3.9a.65.65 0 1 1 0 1.3H2.03a.65.65 0 0 1-.65-.65v-2.6c0-.2.1-.4.27-.52l1.1-.82z"/>
							</g>
						</svg>
					</div>
					
					<!-- Nombre de jurés -->
					<div class="t20 grid col--10">
						<span class="listing__regularText">{{ event.users.length }}</span>
						<svg class="listing__img" xmlns="http://www.w3.org/2000/svg" width="20" height="23" viewBox="0 0 20 23">
							<g fill="#42A5F5" fill-rule="nonzero">
								<path d="M2.2 5.4a.57.57 0 0 1 1.05.44l-2.14 5.19a.57.57 0 0 1-1.05-.43l2.15-5.2z"/>
								<path d="M3.92 10.63a.57.57 0 0 1 0 1.13c-.97 0-1.76-.79-1.76-1.75V5.62a.57.57 0 0 1 1.14 0v4.39c0 .34.27.62.62.62zM5.56 4.58a.57.57 0 0 1 0-1.13h.79a.57.57 0 0 1 0 1.13h-.79z"/>
								<path d="M5.47.05a.57.57 0 1 1 0 1.13 1.13 1.13 0 1 0 0 2.27.57.57 0 0 1 0 1.13 2.27 2.27 0 0 1 0-4.53zM3.86 19.62a.57.57 0 0 1-1.13.07l-.56-9.62a.57.57 0 1 1 1.13-.06l.56 9.61zM7.45 9.97a.57.57 0 1 1 1.13.07l-.54 9.3a.57.57 0 0 1-1.13-.06l.54-9.3z"/>
								<path d="M7.46 18.85c.31 0 .56.25.56.56 0 .45-.36.81-.8.81H5.58a.81.81 0 0 1-.8-.8v-3.97a.57.57 0 0 1 1.13 0v3.64h1.07c.1-.15.27-.24.47-.24zM14.85 4.58a.57.57 0 0 1 0-1.13h.78A2.27 2.27 0 0 1 17.9 5.7v4.31c0 .97-.79 1.76-1.76 1.76a.57.57 0 0 1 0-1.14c.35 0 .62-.27.62-.62v-4.3c0-.63-.5-1.14-1.13-1.14h-.78z"/>
								<path d="M14.73.05a.57.57 0 1 1 0 1.13 1.13 1.13 0 0 0 0 2.27.57.57 0 1 1 0 1.13 2.27 2.27 0 0 1 0-4.53zM16.63 5.2a.57.57 0 0 1 1.06-.4l2.22 5.81a.57.57 0 1 1-1.06.4L16.63 5.2zM16.74 9.97a.57.57 0 0 1 1.13.07l-.54 9.3a.57.57 0 0 1-1.13-.06l.54-9.3z"/>
								<path d="M16.77 18.85c.32 0 .57.25.57.56 0 .45-.36.81-.81.81h-1.62a.81.81 0 0 1-.81-.8v-4.23a.57.57 0 0 1 1.13 0v3.9h1.08c.1-.15.27-.24.46-.24zM6.74 7.45a.57.57 0 1 1 1.05.43l-2.15 5.2a.57.57 0 1 1-1.05-.43l2.15-5.2z"/>
								<path d="M10.1 6.63a.57.57 0 0 1 0-1.13h.78a2.27 2.27 0 0 1 2.27 2.26v4.32a1.75 1.75 0 0 1-1.75 1.75H8.45a1.75 1.75 0 0 1-1.75-1.75V7.67a.57.57 0 0 1 1.13 0v4.4a.62.62 0 0 0 .62.62h2.95a.62.62 0 0 0 .61-.61V7.76c0-.62-.5-1.13-1.13-1.13h-.78z"/>
								<path d="M10 2.1a.57.57 0 0 1 0 1.13 1.13 1.13 0 1 0 0 2.27.57.57 0 0 1 0 1.13 2.27 2.27 0 1 1 0-4.53zM11.87 7.25a.57.57 0 1 1 1.06-.4l2.23 5.81a.57.57 0 0 1-1.06.4l-2.23-5.81zM8.4 21.67a.57.57 0 1 1-1.13.07l-.57-9.61a.57.57 0 1 1 1.13-.07l.57 9.61zM11.99 12.02a.57.57 0 0 1 1.13.07l-.55 9.3a.57.57 0 1 1-1.13-.06l.55-9.31z"/>
								<path d="M12 20.9c.3 0 .56.25.56.56 0 .45-.37.81-.81.81h-1.62a.81.81 0 0 1-.81-.81v-5.38a.57.57 0 0 1 1.13 0v5.06h1.08c.1-.15.27-.25.46-.25z"/>
							</g>
						</svg>
					</div>
					
					<!-- Date de commencement -->
					<div class="t20 grid col--10">
						<span class="listing__regularText">{{ event.date_event | formatDate }}</span>
						<svg class="listing__img" xmlns="http://www.w3.org/2000/svg" width="18" height="21" viewBox="0 0 18 21">
							<g fill="#42A5F5" fill-rule="nonzero">
								<circle cx=".82" cy="5.54" r="1"/>
								<path d="M11.48 3.43a.47.47 0 1 1 0 .95H7.9a.47.47 0 1 1 0-.95h3.58z"/>
								<path d="M7.9 3.43a.47.47 0 0 1 0 .95H6.2a.47.47 0 0 1 0-.95h1.7zM2.38 4.82a.47.47 0 1 1-.53-.78c.57-.4 1.25-.6 1.95-.6h.5a.47.47 0 1 1 0 .94h-.5c-.5 0-1 .15-1.42.44zM13.39 4.38a.47.47 0 0 1 0-.95h.39a3.47 3.47 0 0 1 3.46 3.46v9.98a3.47 3.47 0 0 1-3.47 3.46H3.8a3.47 3.47 0 0 1-3.46-3.46V6.89a.47.47 0 0 1 .94 0v9.98a2.52 2.52 0 0 0 2.52 2.51h9.97a2.52 2.52 0 0 0 2.52-2.51V6.89a2.52 2.52 0 0 0-2.52-2.51h-.38z"/>
								<path d="M4.77 3.9a.47.47 0 0 1-.94 0V1.98a1.42 1.42 0 0 1 2.83 0v3.23a.47.47 0 0 1-.95 0V1.98a.47.47 0 0 0-.94 0V3.9zM11.96 3.9a.47.47 0 0 1-.95 0V1.98a1.42 1.42 0 0 1 2.84 0v3.23a.47.47 0 0 1-.95 0V1.98a.47.47 0 0 0-.94 0V3.9zM6.04 10l-1.3 1.3a.47.47 0 1 1-.66-.67l2.1-2.1c.3-.3.8-.09.8.33v6.75h4.42v-6.7a.47.47 0 0 1 .95 0v7.17c0 .26-.21.48-.47.48H6.52a.47.47 0 0 1-.48-.48V10z"/>
								<path d="M13.25 12.13a.47.47 0 1 1 0 .94h-4.1a.47.47 0 0 1-.47-.47V8.92a.47.47 0 0 1 .94 0v3.21h3.63z"/>
							</g>
						</svg>
					</div>
					
					<div class="t20 col--20 listing__tar">
						<span class="listing__status listing__status--start">{{ event.status | formatStatus}}</span>
					</div>
				</router-link>
				</div>
				
				<div v-else="jiri.currentUser.is_admin == 0">
					<router-link class="grid grid--padding" :to="{ name: 'StudentList', params: { id: event.id, title: event.course_name } }">
						<!-- Title -->
						<div class="t20 col--20">
							<span class="listing__eventTitle">{{ event.course_name }}</span>
						</div>
						
						<!-- Year -->
						<div class="t20 col--10">
							<span class="listing__regularText">{{ event.academic_year }}</span>
						</div>
						
						<!-- Exam session -->
						<div class="t20 col--20">
							<span class="listing__regularText">{{ event.exam_session | formatSession }}</span>
						</div>
						
						<!-- Nombre d'étudiants inscrits -->
						<div class="t20 grid col--10">
							<span class="listing__regularText">{{ event.students.length }}</span>
							<svg class="listing__img" xmlns="http://www.w3.org/2000/svg" width="26" height="17" viewBox="0 0 26 17">
								<g fill="#42A5F5" fill-rule="nonzero">
									<path d="M6.26 7.44a.65.65 0 0 1-.45 1.22L1.58 7.11a1.45 1.45 0 0 1 0-2.71L12.5.33c.33-.12.68-.12 1 0L24.44 4.4a1.45 1.45 0 0 1 0 2.71l-4.24 1.54a.28.28 0 0 0-.18.27v1.61a4.59 4.59 0 0 1-2.86 4.27l-2.4.98a4.59 4.59 0 0 1-3.48 0l-2.42-.98A4.59 4.59 0 0 1 6 10.55a.65.65 0 0 1 1.3 0c0 1.34.8 2.54 2.05 3.04l2.4.99c.8.32 1.7.32 2.5 0l2.4-.99a3.29 3.29 0 0 0 2.06-3.05V8.92c0-.67.4-1.26 1.04-1.49l4.22-1.54a.15.15 0 0 0 0-.28L13.05 1.55a.15.15 0 0 0-.1 0L2.04 5.61a.15.15 0 0 0-.1.14c0 .06.04.12.1.14l4.22 1.55z"/>
									<path d="M16.92 8.4a.65.65 0 1 1 .42 1.22l-3.12 1.08c-.79.27-1.64.27-2.43 0L3.7 7.9a.65.65 0 0 1 .42-1.23l8.1 2.8c.52.17 1.07.17 1.59 0l3.12-1.08z"/>
									<path d="M2.75 11.76V9.53A3.5 3.5 0 0 1 5.7 6.08l7.2-1.13a.65.65 0 0 1 .2 1.28L5.9 7.36a2.2 2.2 0 0 0-1.85 2.17v2.56c0 .2-.1.4-.26.52l-1.1.82v1.62H3.9a.65.65 0 1 1 0 1.3H2.03a.65.65 0 0 1-.65-.65v-2.6c0-.2.1-.4.27-.52l1.1-.82z"/>
								</g>
							</svg>
						</div>
						
						<!-- Nombre de jurés -->
						<div class="t20 grid col--10">
							<span class="listing__regularText">{{ event.users.length }}</span>
							<svg class="listing__img" xmlns="http://www.w3.org/2000/svg" width="20" height="23" viewBox="0 0 20 23">
								<g fill="#42A5F5" fill-rule="nonzero">
									<path d="M2.2 5.4a.57.57 0 0 1 1.05.44l-2.14 5.19a.57.57 0 0 1-1.05-.43l2.15-5.2z"/>
									<path d="M3.92 10.63a.57.57 0 0 1 0 1.13c-.97 0-1.76-.79-1.76-1.75V5.62a.57.57 0 0 1 1.14 0v4.39c0 .34.27.62.62.62zM5.56 4.58a.57.57 0 0 1 0-1.13h.79a.57.57 0 0 1 0 1.13h-.79z"/>
									<path d="M5.47.05a.57.57 0 1 1 0 1.13 1.13 1.13 0 1 0 0 2.27.57.57 0 0 1 0 1.13 2.27 2.27 0 0 1 0-4.53zM3.86 19.62a.57.57 0 0 1-1.13.07l-.56-9.62a.57.57 0 1 1 1.13-.06l.56 9.61zM7.45 9.97a.57.57 0 1 1 1.13.07l-.54 9.3a.57.57 0 0 1-1.13-.06l.54-9.3z"/>
									<path d="M7.46 18.85c.31 0 .56.25.56.56 0 .45-.36.81-.8.81H5.58a.81.81 0 0 1-.8-.8v-3.97a.57.57 0 0 1 1.13 0v3.64h1.07c.1-.15.27-.24.47-.24zM14.85 4.58a.57.57 0 0 1 0-1.13h.78A2.27 2.27 0 0 1 17.9 5.7v4.31c0 .97-.79 1.76-1.76 1.76a.57.57 0 0 1 0-1.14c.35 0 .62-.27.62-.62v-4.3c0-.63-.5-1.14-1.13-1.14h-.78z"/>
									<path d="M14.73.05a.57.57 0 1 1 0 1.13 1.13 1.13 0 0 0 0 2.27.57.57 0 1 1 0 1.13 2.27 2.27 0 0 1 0-4.53zM16.63 5.2a.57.57 0 0 1 1.06-.4l2.22 5.81a.57.57 0 1 1-1.06.4L16.63 5.2zM16.74 9.97a.57.57 0 0 1 1.13.07l-.54 9.3a.57.57 0 0 1-1.13-.06l.54-9.3z"/>
									<path d="M16.77 18.85c.32 0 .57.25.57.56 0 .45-.36.81-.81.81h-1.62a.81.81 0 0 1-.81-.8v-4.23a.57.57 0 0 1 1.13 0v3.9h1.08c.1-.15.27-.24.46-.24zM6.74 7.45a.57.57 0 1 1 1.05.43l-2.15 5.2a.57.57 0 1 1-1.05-.43l2.15-5.2z"/>
									<path d="M10.1 6.63a.57.57 0 0 1 0-1.13h.78a2.27 2.27 0 0 1 2.27 2.26v4.32a1.75 1.75 0 0 1-1.75 1.75H8.45a1.75 1.75 0 0 1-1.75-1.75V7.67a.57.57 0 0 1 1.13 0v4.4a.62.62 0 0 0 .62.62h2.95a.62.62 0 0 0 .61-.61V7.76c0-.62-.5-1.13-1.13-1.13h-.78z"/>
									<path d="M10 2.1a.57.57 0 0 1 0 1.13 1.13 1.13 0 1 0 0 2.27.57.57 0 0 1 0 1.13 2.27 2.27 0 1 1 0-4.53zM11.87 7.25a.57.57 0 1 1 1.06-.4l2.23 5.81a.57.57 0 0 1-1.06.4l-2.23-5.81zM8.4 21.67a.57.57 0 1 1-1.13.07l-.57-9.61a.57.57 0 1 1 1.13-.07l.57 9.61zM11.99 12.02a.57.57 0 0 1 1.13.07l-.55 9.3a.57.57 0 1 1-1.13-.06l.55-9.31z"/>
									<path d="M12 20.9c.3 0 .56.25.56.56 0 .45-.37.81-.81.81h-1.62a.81.81 0 0 1-.81-.81v-5.38a.57.57 0 0 1 1.13 0v5.06h1.08c.1-.15.27-.25.46-.25z"/>
								</g>
							</svg>
						</div>
						
						<!-- Date de commencement -->
						<div class="t20 grid col--10">
							<span class="listing__regularText">{{ event.date_event | formatDate }}</span>
							<svg class="listing__img" xmlns="http://www.w3.org/2000/svg" width="18" height="21" viewBox="0 0 18 21">
								<g fill="#42A5F5" fill-rule="nonzero">
									<circle cx=".82" cy="5.54" r="1"/>
									<path d="M11.48 3.43a.47.47 0 1 1 0 .95H7.9a.47.47 0 1 1 0-.95h3.58z"/>
									<path d="M7.9 3.43a.47.47 0 0 1 0 .95H6.2a.47.47 0 0 1 0-.95h1.7zM2.38 4.82a.47.47 0 1 1-.53-.78c.57-.4 1.25-.6 1.95-.6h.5a.47.47 0 1 1 0 .94h-.5c-.5 0-1 .15-1.42.44zM13.39 4.38a.47.47 0 0 1 0-.95h.39a3.47 3.47 0 0 1 3.46 3.46v9.98a3.47 3.47 0 0 1-3.47 3.46H3.8a3.47 3.47 0 0 1-3.46-3.46V6.89a.47.47 0 0 1 .94 0v9.98a2.52 2.52 0 0 0 2.52 2.51h9.97a2.52 2.52 0 0 0 2.52-2.51V6.89a2.52 2.52 0 0 0-2.52-2.51h-.38z"/>
									<path d="M4.77 3.9a.47.47 0 0 1-.94 0V1.98a1.42 1.42 0 0 1 2.83 0v3.23a.47.47 0 0 1-.95 0V1.98a.47.47 0 0 0-.94 0V3.9zM11.96 3.9a.47.47 0 0 1-.95 0V1.98a1.42 1.42 0 0 1 2.84 0v3.23a.47.47 0 0 1-.95 0V1.98a.47.47 0 0 0-.94 0V3.9zM6.04 10l-1.3 1.3a.47.47 0 1 1-.66-.67l2.1-2.1c.3-.3.8-.09.8.33v6.75h4.42v-6.7a.47.47 0 0 1 .95 0v7.17c0 .26-.21.48-.47.48H6.52a.47.47 0 0 1-.48-.48V10z"/>
									<path d="M13.25 12.13a.47.47 0 1 1 0 .94h-4.1a.47.47 0 0 1-.47-.47V8.92a.47.47 0 0 1 .94 0v3.21h3.63z"/>
								</g>
							</svg>
						</div>
						
						<div class="t20 col--20 listing__tar">
							<span class="listing__status listing__status--start">{{ event.status | formatStatus}}</span>
						</div>
					</router-link>
				</div>
				
			</div>
		</div>
		
	</div>
</template>

<script>
    import moment from 'moment';
		
    export default {
        data() {
            return {
                createEvent: new JiriForm({
                    course_name: '',
                    academic_year: '',
                    exam_session: '',
                    date_event: '',
										dialogCreateEvent: false
                }),
                loading: true,
                errored: false,
                events: []
            }
        },

        mounted() {
            var self = this;

            this.getEvents();
            this.$on('updateListEvents', () => {
                self.getEvents();
            });
        },

        methods: {
            /*
						 * Get list of events
						 */
            getEvents() {
                this.loading = true;

                axios.get('/api/events')
                    .then(response => {
                        this.events = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => this.loading = false);
            },

            /*
						 * Create a new event in database
						 */
            store() {
                Jiri.post( 'api/events', this.createEvent )
                    .then( response => {
                        this.$emit( 'updateListEvents' );

                        // Reset form
                        this.createEvent.course_name = '';
                        this.createEvent.academic_year = '';
                        this.createEvent.exam_session = '';
                        this.createEvent.date_event = '';

                    } ).catch( error => {
                    console.log( error );
                } );
            },
						
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
						
				},
    }
</script>
