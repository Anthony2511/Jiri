<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
<style>
	@media  only screen and (max-width: 600px) {
		.inner-body {
			width: 100% !important;
		}

		.footer {
			width: 100% !important;
		}
	}

	@media  only screen and (max-width: 500px) {
		.button {
			width: 100% !important;
		}
	}
</style>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;"><tbody><tr>
		<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
			<table class="content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
				<tbody><tr>
					<td class="header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center;">
						<a target="_blank" rel="noopener noreferrer" href="http://jiri.test" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
							{{ config('app.name') }}
						</a>
					</td>
				</tr>
				<!-- Email Body --><tr>
					<td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; border-bottom: 1px solid #EDEFF2; border-top: 1px solid #EDEFF2; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
						<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
							<!-- Body content --><tbody><tr>
								<td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
									<h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">Bienvenue sur Jiri !</h1>
									<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Vous venez d’être ajouté en tant que membre du jury dans la base de donnée de l’application Jiri, la plateforme de gestion des jurys de fin d’année de la HEPL section web.</p>
									<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Veuillez suivre le lien suivant pour générer un mot de passe pour votre compte.</p>
									<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;"><tbody><tr>
											<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
												<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;"><tbody><tr>
														<td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
															<table border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;"><tbody><tr>
																	<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
																		<a target="_blank" rel="noopener noreferrer" href="{{ url(config('app.url').route('password.reset', $token, false)) }}" class="button button-primary" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #409eff; border-top: 10px solid #409eff; border-right: 18px solid #409eff; border-bottom: 10px solid #409eff; border-left: 18px solid #409eff;">Générer un mot de passe</a>
																	</td>
																</tr></tbody></table>
														</td>
													</tr></tbody></table>
											</td>
										</tr></tbody></table>
									<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Si vous ne comprenez pas pourquoi vous avez reçu cet email, vous pouvez contacter le responsable de la section web à l’adresse email suivante : <a href="{{ env('EMAIL_RESPONSABLE') }}">{{ env('EMAIL_RESPONSABLE') }}</a>.</p>
									<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Cordialement,<br>Jiri</p>
									<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;"><tbody><tr>
											<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
												<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">Si vous n’arrivez pas à cliquer sur le bouton « Générer un mot de passe », vous pouvez copier le lien suivant dans votre navigateur: <a target="_blank" rel="noopener noreferrer" href="{{ url(config('app.url').route('password.reset', $token, false)) }}" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #3869D4;">{{ url(config('app.url').route('password.reset', $token, false)) }}</a></p>
											</td>
										</tr></tbody></table>
								</td>
							</tr>
							</tbody></table>
					</td>
				</tr>
				<tr>
					<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
						<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;"><tbody><tr>
								<td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
									<p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">© 2019 Jiri. Tous droits réservés..</p>
								</td>
							</tr></tbody></table>
					</td>
				</tr>
				</tbody></table>
		</td>
	</tr></tbody></table>


<div id="ConnectiveDocSignExtentionInstalled" data-extension-version="1.0.4"></div></body></html>
