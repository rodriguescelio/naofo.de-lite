<template>
	<div class="home">
		<div class="box">
			<h1>{{ config.site_name }}</h1>
			<h3>Encurtador higiênico de chorume</h3>
			<div class="form">
				<input type="text" v-model="url" @keypress="enter" autofocus />
				<button @click="go"><i class="fa fa-camera"></i></button>
			</div>
		</div>
	</div>
</template>

<script>
import swal from 'sweetalert'
import { mapState } from 'vuex'
export default {
	name: 'Home',
	computed: {
		...mapState(['config'])
	},
	methods: {
		enter() {
			if(event.key == 'Enter') {
				this.go()
			}
		},
		go() {
			console.log(this.config)
			if(this.url) {
				this.$router.push({
					name: 'Confirm',
					params: {
						url: encodeURI(this.url)
					}
				})
			} else {
				swal({
					title: "Insira a url da página",
					text: "Precisamos da url da página para continuarmos",
					icon: "warning"
				})
			}
		}
	},
	data () {
		return {
			url: null
		}
	}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="less">
	.home {
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
				input {
					display: inline-block;
					width: 100%;
					background: #fff;
					border: solid 1px #e4e4e4;
					outline: none;
					padding: 15px;
					color: #7d7d7d;
					padding-right: 50px;
					border-radius: 15px;
					text-align: center;
					box-sizing: border-box;
					-webkit-box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
					-moz-box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
					box-shadow: 0px 0px 24px -4px rgba(0,0,0,0.31);
				}
				button {
					position: absolute;
					right: 10px;
					border: none;
					top: 11px;
					background: transparent;
					outline: none;
					i {
						color: #c7c7c7;
						font-size: 25px;
					}
				}
			}

		}
	}
</style>
