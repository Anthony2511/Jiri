<div class="bloc-header grid grid-sb" style="width: 100%;">

	<div v-if="$route.meta.page == 'event'">
		<h2 role="heading" aria-level="2" id="header-title" class="header__title title-header">
			@{{ $route.meta.title }}
		</h2>
	</div>
	<div v-else>
		<h2 role="heading" aria-level="2"  id="header-title" class="header__title title-header">@{{ $route.meta.title }}</h2>
	</div>

	<div class="header__profile">
		<span class="header__profile__name">@{{ jiri.currentUser.name }}</span>
		<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
			<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" class="header__svg">
				<defs><linearGradient id="a" x1="119.51%" x2="-55.13%" y1="118.74%" y2="-27.57%"><stop offset="0%" stop-color="#27E9DE"/><stop offset="52%" stop-color="#03A4EC"/><stop offset="100%" stop-color="#2547A8"/></linearGradient></defs><g fill="url(#a)" fill-rule="nonzero"><path d="M16.4 9.11a1 1 0 1 1 .95-1.76 6.83 6.83 0 1 1-6.7.12 1 1 0 0 1 1 1.72 4.83 4.83 0 1 0 4.74-.08z"/><path d="M15.1 13.32a1 1 0 0 1-2 0V5.08a1 1 0 0 1 2 0v8.24z"/><path d="M18.45.8a1 1 0 0 1-.64 1.89 11.65 11.65 0 1 0 4.53 2.82 1 1 0 1 1 1.42-1.42A13.65 13.65 0 1 1 18.45.8z"/><circle cx="20.94" cy="3.06" r="1"/></g>
			</svg>
		</a>
	</div>

	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		{{ csrf_field() }}
	</form>
</div>


<div class="sub-nav" v-if="jiri.currentUser.is_admin == 1 && $route.meta.page == 'event'">
	<router-link :to="{ name: 'EventDashboard' }" class="sub-nav__item">Tableau de bord</router-link>
	<router-link :to="{ name: 'EventJury' }" class="sub-nav__item">Membres du jury</router-link>
	<router-link :to="{ name: 'EventProjects' }" class="sub-nav__item">Projets</router-link>
	<router-link :to="{ name: 'EventStudents' }" class="sub-nav__item">Étudiants</router-link>
	<router-link :to="{ name: 'EventSettings' }" class="sub-nav__item">Paramètres de l’épreuve</router-link>
</div>
