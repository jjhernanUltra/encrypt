<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const textProcess = ref(false);
const checked = ref(false);

textProcess.value = usePage().props.textProcess;
checked.value = usePage().props.checked;

const form = useForm({
    message: null,
    type: null
});

const typeMessage = ref(null);

function sendForm() {
    console.log(typeMessage.value.checked);
    form.transform((data) => ({
        ...data,
        type: typeMessage.value.checked == false ? 'encrypt' : 'decrypt'
    })).get('/procesar');
}

function copyText() {
    const textArea = document.querySelector('#message');
    navigator.clipboard.writeText(textArea.value);
}

</script>

<template>
    <AppLayout title="Inicio">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inicio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
                    <div class="flex items-center gap-3 justify-center">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-300">Encriptar</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" ref="typeMessage" :checked="checked">
                            <div class="w-11 h-6 bg-green-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-300">Desencriptar</span>
                    </div>
                    <div class="">
                        <form @submit.prevent="submit">
                            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mensaje</label>
                            <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                                    <label for="comment" class="sr-only">Mensaje</label>
                                    <textarea
                                      id="comment"
                                      rows="4"
                                      class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"
                                      placeholder="Escribe aquí tu mensaje..."
                                      required
                                      v-model="form.message"></textarea>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
                                    <button
                                      type="submit"
                                      class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"
                                      @click="sendForm">
                                        Enviar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div v-if="textProcess">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Resultado</label>
                        <div class="relative">
                            <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>{{ textProcess }}</textarea>
                            <button @click="copyText" type="button" class="absolute right-0 -top-12 m-2 text-white bg-blue-300 hover:bg-blue-200 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Copiar Texto</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
