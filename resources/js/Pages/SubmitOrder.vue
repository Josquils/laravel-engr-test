<style>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css");
</style>

<template>
    <GuestLayout>
        <div
            v-if="successMessage"
            class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3"
            role="alert"
        >
            <svg
                class="fill-current w-4 h-4 mr-2"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <path
                    d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"
                />
            </svg>
            <p>{{ successMessage }}</p>
        </div>

        <Head title="Submit Order" />

        <div class="card-header">Submit An Order</div>

        <div class="order-form">
            <div
                class="mt-10 mb-12 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
            >
                <!-- HMO -->
                <div class="col-span-full">
                    <label
                        for="hmoCode"
                        class="block text-sm font-medium leading-6 text-gray-900"
                    >
                        HMO Code:
                    </label>
                    <div class="mt-2">
                        <select
                            id="hmo"
                            v-model="form.hmoCode"
                            class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                        >
                            <option disabled value="" selected>
                                --- Select HMO ---
                            </option>
                            <option
                                v-for="hmo in hmos"
                                :key="hmo.code"
                                :value="hmo.code"
                            >
                                {{ hmo.name }}
                            </option>
                        </select>
                    </div>
                    <p v-if="form.errors.hmoCode" class="text-red-700">
                        {{ form.errors.hmoCode }}
                    </p>
                </div>

                <!-- Provider -->
                <div class="col-span-full">
                    <label
                        for="providerName"
                        class="block text-sm font-medium leading-6 text-gray-900"
                    >
                        Provider Name:
                    </label>
                    <div class="mt-1">
                        <input
                            id="providerName"
                            class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                            placeholder="Provider Name"
                            type="text"
                            v-model="form.providerName"
                            required
                        />
                    </div>
                    <p v-if="form.errors.providerName" class="text-red-700">
                        {{ form.errors.providerName }}
                    </p>
                </div>

                <!-- Encounter Date -->
                <div class="col-span-full mt-0">
                    <label
                        for="encounterDate"
                        class="block text-sm font-medium leading-6 text-gray-900"
                    >
                        Encounter Date:
                    </label>
                    <div class="mt-1">
                        <input
                            id="encounterDate"
                            class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                            placeholder="Date"
                            type="date"
                            v-model="form.encounterDate"
                            required
                        />
                    </div>
                    <p v-if="form.errors.encounterDate" class="text-red-700">
                        {{ form.errors.encounterDate }}
                    </p>
                </div>

                <!-- Items -->
                <div class="col-span-full order-itmes">
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th>Sub Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class="mt-2"
                                v-for="(item, index) in form.items"
                                :key="index"
                            >
                                <td>
                                    <input
                                        class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                                        type="text"
                                        v-model="item.name"
                                        placeholder="Item Name"
                                        required
                                    />
                                </td>
                                <td>
                                    <input
                                        class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                                        type="number"
                                        v-model.number="item.unitPrice"
                                        @input="calculateSubtotal(item)"
                                        placeholder="Unit Price"
                                        min="0"
                                        required
                                    />
                                </td>
                                <td>
                                    <input
                                        class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                                        type="number"
                                        v-model.number="item.quantity"
                                        @input="calculateSubtotal(item)"
                                        placeholder="Quantity"
                                        min="0"
                                        required
                                    />
                                </td>
                                <td>
                                    <input
                                        class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                                        type="number"
                                        :value="item.subTotal"
                                        readonly
                                    />
                                </td>
                                <td>
                                    <button
                                        class="text-red-700"
                                        type="button"
                                        @click="removeItem(index)"
                                    >
                                        <i
                                            class="fa fa-trash"
                                            aria-hidden="true"
                                        >
                                        </i>
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Add Items\ -->
                    <button class="mt-6 px-4" type="button" @click="addItem">
                        <i class="fa fa-plus-square" aria-hidden="true"></i> Add
                        More Items
                    </button>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="my-2">
                <label>Total Amount:</label>
                <input
                    class="ml-2 w-auto rounded-md border-1 py-1.5 text-gray-900 shadow-sm"
                    type="number"
                    :value="totalAmount"
                    readonly
                />
                <p v-if="form.errors.total" class="text-red-700">
                    {{ form.errors.total }}
                </p>
            </div>

            <!-- Submit -->
            <button
                type="button"
                @click="submitOrder"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm"
            >
                Submit Order
            </button>
        </div>
    </GuestLayout>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

console.log("submit order page loaded");

defineProps({ hmos: Object });

const form = useForm({
    hmoCode: "",
    providerName: "",
    encounterDate: "",
    items: [{ name: "", unitPrice: 0, quantity: 0, subTotal: 0 }],
    total: 0,
});

const successMessage = ref("");

const addItem = () => {
    form.items.push({ name: "", unitPrice: 0, quantity: 1, subTotal: 0 });
};

const removeItem = (index) => {
    form.items.splice(index, 1);
};

const calculateSubtotal = (item) => {
    item.subTotal = item.unitPrice * item.quantity;
};

const totalAmount = computed(() => {
    return form.items.reduce((sum, item) => sum + item.subTotal, 0);
});

const submitOrder = () => {
    console.log("submitted...");
    form.total = totalAmount;
    form.post("/orders/save", {
        onSuccess: () => {
            resetForm();

            successMessage.value = "Order submitted successfully!";
            setTimeout(() => {
                successMessage.value = "";
            }, 3000);
        },
        onError: () => {
            console.log("Error occured");
            console.log(form.errors);
        },
    });
};

const resetForm = () => {
    form.hmoCode = "";
    form.providerName = "";
    form.encounterDate = "";
    form.items = [{ name: "", unitPrice: 0, quantity: 0, subTotal: 0 }];
};
</script>
