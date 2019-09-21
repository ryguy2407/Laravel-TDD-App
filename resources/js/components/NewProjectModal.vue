<template>
	<modal name="new-project" classes="p-10 bg-card rounded-lg" height="auto">
		<form @submit.prevent="submit">
			<h1 class="font-normal mb-16 text-2xl text-center">Let's start something new</h1>

			<div class="flex">
				<div class="flex-1 mr-4">
					<div class="mb-4">
						<label for="title" class="text-sm block w-full mb-2">Title</label>
						<input
								type="text"
								id="title"
								class="w-full rounded block border border-muted-ligt p-2 text-xs"
								v-model="form.title">
						<span class="text-xs" v-if="errors.title" v-text="errors.title[0]"></span>
					</div>

					<div class="mb-4">
						<label for="description" class="text-sm block w-full mb-2">Description</label>
						<textarea
								name="description"
								class="w-full rounded block border border-muted-ligt p-2 text-xs"
								id="description"
								cols="30"
								rows="10" v-model="form.description"></textarea>
						<span class="text-xs" v-if="errors.description" v-text="errors.description[0]"></span>
					</div>
				</div>
				<div class="flex-1 ml-4">

					<div class="mb-4">
						<label for="title" class="text-sm block w-full mb-2">Need some tasks</label>
						<input
								type="text"
								class="w-full rounded block border border-muted-ligt p-2 text-xs mb-2"
								placeholder="Task one"
								v-for="task in form.tasks"
								v-model="task.value">
					</div>

					<button @click="addTask">
						Add a new task
					</button>

				</div>
			</div>

			<footer class="flex justify-end">
				<button class="button" @click="$modal.hide('new-project')" style="background: #fff;color:#444;">Cancel Project</button>
				<button class="button ml-4">Create Project</button>
			</footer>
		</form>
	</modal>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					title: '',
					description: '',
					tasks: [
						{ value: '' },
					]
				},

				errors: {}
			};
		},

		methods: {
			addTask() {
				this.form.tasks.push({ value: '' })
			},

			submit() {
				axios.post('/projects', this.form)
						.then(response => {
							location = response.data.message;
						})
						.catch(error => {
							this.errors = error.response.data.errors
						});
			}
		}
	}
</script>