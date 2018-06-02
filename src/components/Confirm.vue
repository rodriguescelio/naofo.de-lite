<template>
	<div class="confirm">
		<div class="box">
			<h1>{{ config.site_name }}</h1>
			<h3>Encurtador higiênico de chorume</h3>
			<div class="form">
                <input type="text" v-model="link" />
				<input type="text" placeholder="Titulo" ref="titulo" v-model="title" />
                <vue-recaptcha
                    class="recaptcha"
                    @verify="onCaptchaVerified"
                    @expired="onCaptchaExpired"
                    :sitekey="config.recaptcha.site_key">
                </vue-recaptcha>
                <button @click="generate">Gerar</button>
				<loading
					:show="loading"
					label="Carregando...">
				</loading>
			</div>
		</div>
	</div>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha'
import loading from 'vue-full-loading'
import { mapState } from 'vuex'
import axios from 'axios'
import sha1 from 'js-sha1'
import swal from 'sweetalert'

export default {
	name: 'Confirm',
	props: ['url'],
    components: {
		VueRecaptcha,
		loading
	},
	computed: {
		...mapState(['config', 'archive'])
	},
	methods: {
		onCaptchaVerified(recaptchaToken) {
			this.captcha = true
		},
		onCaptchaExpired() {
			this.captcha = false
		},
		generate() {
			if(this.captcha) {
				this.loading = true
				axios.post(this.config.php_url + '/save.php', {
					url: this.link
				})
				.then((res) => {
					
					let time = new Date().getTime().toString()
					let full_hash = sha1(time)
					let hash = full_hash.substring(0, 7)

					database.ref('saves/' + hash).set({
						full_hash,
						title: this.title,
						url: res.data.url,
						ip: res.data.ip,
						acessos: 0,
						time
					})

					swal({
						title: window.location.origin + '/#/' + hash,
						icon: "success",
						buttons: {
							cancel: 'Sair',
							confirm: 'Visualizar a página'
						}
					})
					.then((click) => {
						this.loading = false
						if(click) {
							this.$router.push({
								name: 'Viewer',
								params: {
									hash
								}
							})
						} else {
							this.$router.push({
								name: 'Home'
							})
						}
					})

				})
				.catch((err) => {
					throw err
				})
			} else {
				swal({
					title: "Validação",
					text: "Por favor valide o captcha para que possamos continuar",
					icon: "warning"
				})
			}
		}
	},
	mounted() {
		this.$refs.titulo.focus()
	},
	data () {
		return {
			title: null,
			captcha: false,
			loading: false,
			link: decodeURI(this.url)
		}
    },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="less">
	.confirm {
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		background: #f5f5f5;

		.box {
			width: 600px;

			h1, h3 {
				width: 100%;
				text-align: center;
			}

			h1 {
				font-size: 60px;
				margin: 0;
			}

			h3 {
				margin-top: 0;
				font-weight: 300;
			}

			.form {
                position: relative;
                text-align: center;
				input {
					display: inline-block;
					width: 100%;
					background: #fff;
					border: solid 1px #e4e4e4;
					outline: none;
					padding: 15px;
					color: #7d7d7d;
					border-radius: 15px;
					text-align: center;
                    box-sizing: border-box;
                    margin-bottom: 10px;
					-webkit-box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
					-moz-box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
					box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
                }
                .recaptcha {
                    margin-left: ~"calc(50% - 152px)";
                }
				button {
					background: #fff;
                    border: solid 1px #e4e4e4;
                    outline: none;
                    padding: 10px 25px;
                    color: #7d7d7d;
                    border-radius: 10px;
                    margin-top: 10px;
                    -webkit-box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
					-moz-box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
					box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
				}
			}

		}
	}
</style>
