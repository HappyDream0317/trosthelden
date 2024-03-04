<template>
    <default-layout>
        <div class="row">
            <div class="col-sm-12 col-lg-7">
                <ValidationObserver
                    tag="form"
                    ref="observer"
                    @submit="submit"
                    v-slot="{ meta: { valid, dirty} }"
                >
                    <div
                        class="rounded-box rounded-box--100h p-4 position-relative"
                    >
                        <h3 class="mb-4">Deine Bestelldaten</h3>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 col-lg-12">
                                <select
                                    v-model="customer.CustomFields.Gender"
                                    id="customer.CustomFields.Gender"
                                    class="form-control"
                                    name="customer.CustomFields.Gender"
                                >
                                    <option :value="null" disabled>
                                        Anrede
                                    </option>
                                    <option
                                        v-for="item in genres"
                                        :value="item.code"
                                        v-bind:key="item.code"
                                    >
                                        {{ item.name }}
                                    </option>
                                </select>
                                <span
                                    class="validation-error error-message"
                                ></span>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.FirstName"
                                    rules="required|max:64"
                                    v-model="customer.FirstName"
                                    v-slot="{ errorMessage, field  }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.FirstName"
                                            type="text"
                                            class="form-control"
                                            name="customer.FirstName"
                                            placeholder="Vorname *"
                                            maxlength="64"
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.LastName"
                                    rules="required|max:64"
                                    v-model="customer.LastName"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.LastName"
                                            type="text"
                                            class="form-control"
                                            name="customer.LastName"
                                            placeholder="Nachname *"
                                            maxlength="64"
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.EmailAddress"
                                    rules="required|email"
                                    v-model="customer.EmailAddress"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.EmailAddress"
                                            type="email"
                                            class="form-control"
                                            name="customer.EmailAddress"
                                            placeholder="E-Mail-Adresse *"
                                            @keydown.space.prevent
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.PhoneNumber"
                                    rules="max:128"
                                    v-model="customer.PhoneNumber"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.PhoneNumber"
                                            type="text"
                                            class="form-control"
                                            name="customer.PhoneNumber"
                                            placeholder="Telefonnummer"
                                            maxlength="64"
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.Address.Street"
                                    rules="max:64"
                                    v-model="customer.Address.Street"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.Address.Street"
                                            type="text"
                                            class="form-control"
                                            name="customer.Address.Street"
                                            placeholder="Straße"
                                            maxlength="64"
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.Address.HouseNumber"
                                    rules="max:12"
                                    v-model="customer.Address.HouseNumber"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.Address.HouseNumber"
                                            type="text"
                                            class="form-control"
                                            name="customer.Address.HouseNumber"
                                            placeholder="Hausnr."
                                            maxlength="12"
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.Address.PostalCode"
                                    rules="max:12"
                                    v-model="customer.Address.PostalCode"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.Address.PostalCode"
                                            type="text"
                                            class="form-control"
                                            name="customer.Address.PostalCode"
                                            placeholder="PLZ"
                                            maxlength="12"
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <ValidationProvider
                                    as="div"
                                    name="customer.Address.City"
                                    rules="required|max:128"
                                    v-model="customer.Address.City"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <input
                                            v-bind="field"
                                            id="customer.Address.City"
                                            type="text"
                                            class="form-control"
                                            name="customer.Address.City"
                                            placeholder="Ort *"
                                            maxlength="128"
                                        />
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 col-lg-12">
                                <ValidationProvider
                                    as="div"
                                    name="customer.Address.Country"
                                    rules="required"
                                    v-model="customer.Address.Country"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <select
                                            v-bind="field"
                                            id="customer.Address.Country"
                                            class="form-control"
                                            name="customer.Address.Country"
                                        >
                                            <option :value="null" disabled>
                                                Land *
                                            </option>
                                            <option
                                                v-for="item in countries"
                                                :value="item.code"
                                                v-bind:key="item.code"
                                            >
                                                {{ item.name }}
                                            </option>
                                        </select>
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div v-if="couponsAllowed" class="form-group row my-4">
                            <div class="col-sm-12 col-lg-12">
                                <ValidationProvider
                                    as="div" 
                                    name="hasCoupon"
                                    v-model="hasCoupon"
                                    v-slot="{field, handleChange}"
                                    type="checkbox"
                                    :value="true"
                                    :unchecked-value="false"
                                >
                                    <div>
                                        <div class="form-check my-2">
                                            <input
                                                @input="handleChange"
                                                v-bind="field"
                                                id="hasCoupon"
                                                type="checkbox"
                                                class="form-check-input"
                                                name="hasCoupon"
                                            />
                                            <label
                                                for="hasCoupon"
                                                class="form-check-label check-label w-auto font-weight-bold color--primary ms-3"
                                            >
                                                Ich habe einen Coupon-Code.
                                            </label>
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </div>
                                </ValidationProvider>
                            </div>
                            <div v-if="hasCoupon" class="col-sm-12 col-lg-12">
                                <ValidationProvider
                                    as="div"
                                    name="cart.CouponCode"
                                    rules="required"
                                    v-model="cart.CouponCode"
                                    v-slot="{ errorMessage, field }"
                                    :errors="couponHandler.message"
                                >
                                    <div>
                                        <div
                                            class="row form-coupon mt-2"
                                        >
                                            <div class="px-0 col-auto form-coupon-input">
                                                <input
                                                    v-bind="field"
                                                    id="cart.CouponCode"
                                                    type="text"
                                                    class="form-control"
                                                    name="cart.CouponCode"
                                                    placeholder="Ihr Coupon-Code"
                                                />
                                            </div>
                                            <div
                                                class="ms-2 pe-0 col-auto form-coupon-button"
                                            >
                                                <button
                                                    type="button"
                                                    class="btn btn-primary"
                                                    v-on:click="checkCoupon"
                                                    :disabled="
                                                        couponHandler.loading ||
                                                        !cart.CouponCode
                                                    "
                                                >
                                                    Code prüfen
                                                    <span
                                                        v-if="
                                                            couponHandler.loading
                                                        "
                                                        aria-hidden="true"
                                                        class="spinner-grow spinner-grow-sm mb-1"
                                                        role="status"
                                                    ></span>
                                                </button>
                                            </div>
                                        </div>
                                        <span
                                            class="validation-error"
                                            :class="
                                                couponHandler.error &&
                                                'error-message'
                                            "
                                            >{{ couponHandler.message }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <h3 class="mt-4">Zahlungsart wählen</h3>
                        <div class="form-group row mb-0">
                            <div class="col-sm-12 col-lg-12">
                                <ValidationProvider
                                    as="div"
                                    name="payment.bearer"
                                    rules="required"
                                    v-model="payment.bearer"
                                    v-slot="{ errorMessage, field }"
                                >
                                    <div>
                                        <div
                                            class="form-inline form-payment-method mt-4"
                                            v-for="item in availableMethods"
                                            v-bind:key="item.key"
                                        >
                                            <input
                                                v-bind="field"
                                                :id="`payment.bearer.${item.key}`"
                                                class="form-check-input"
                                                type="radio"
                                                name="payment.bearer"
                                                required
                                                :value="item.value"
                                                :title="methods[item.key].name"
                                            />
                                            <label
                                                :for="`payment.bearer.${item.key}`"
                                                class="form-check-label check-label w-auto"
                                            >
                                                <img
                                                    v-for="img in methods[
                                                        item.key
                                                    ].images"
                                                    v-bind:key="`${item.key}.${img}`"
                                                    :src="`/img/${img}.png`"
                                                    alt=""
                                                    class="mx-2 my-1"
                                                    style="height: 25px"
                                                />
                                            </label>
                                        </div>
                                        <span
                                            class="validation-error error-message"
                                            >{{ errorMessage }}</span
                                        >
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div v-show="payment.bearer !== null">
                            <div
                                v-if="
                                    payment.bearer !== null &&
                                    payment.bearer.includes('PayPal')
                                "
                                class="form-group row mb-0"
                            >
                                <div class="col-sm-12 col-lg-12">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.emailAddress"
                                        rules="required|email"
                                        v-model="payment.emailAddress"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <input
                                                v-bind="field"
                                                id="payment.emailAddress"
                                                type="email"
                                                class="form-control"
                                                name="payment.emailAddress"
                                                placeholder="Deine PayPal E-Mail-Adresse *"
                                                @keydown.space.prevent
                                            />
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <div
                                v-if="
                                    payment.bearer !== null &&
                                    payment.bearer.includes('CreditCard')
                                "
                                class="form-group row mb-0"
                            >
                                <div class="col-sm-12 col-lg-12">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.cardHolder"
                                        rules="required"
                                        v-model="payment.cardHolder"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <input
                                                v-bind="field"
                                                id="payment.cardHolder"
                                                type="text"
                                                class="form-control"
                                                name="payment.cardHolder"
                                                placeholder="Karteninhaber *"
                                            />
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.cardNumber"
                                        v-model="payment.cardNumber"
                                        rules="required"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <input
                                                v-bind="field"
                                                id="payment.cardNumber"
                                                type="text"
                                                class="form-control"
                                                name="payment.cardNumber"
                                                placeholder="Kartennummer *"
                                                @keydown.space.prevent
                                            />
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.cvc"
                                        v-model="payment.cvc"
                                        rules="required|max:3"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <input
                                                v-bind="field"
                                                id="payment.cvc"
                                                type="text"
                                                class="form-control"
                                                name="payment.cvc"
                                                placeholder="CVC *"
                                                maxlength="3"
                                                @keydown.space.prevent
                                            />
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.expiryMonth"
                                        rules="required"
                                        v-model="payment.expiryMonth"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <select
                                                v-bind="field"
                                                id="payment.expiryMonth"
                                                class="form-control"
                                                name="payment.expiryMonth"
                                            >
                                                <option
                                                    :value="undefined"
                                                    disabled
                                                >
                                                    Monat des Ablaufs *
                                                </option>
                                                <option
                                                    v-for="month in 12"
                                                    :value="month"
                                                    v-bind:key="month"
                                                >
                                                    {{
                                                        month.toLocaleString(
                                                            "en-US",
                                                            {
                                                                minimumIntegerDigits: 2,
                                                                useGrouping: false,
                                                            }
                                                        )
                                                    }}
                                                </option>
                                            </select>
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.expiryYear"
                                        rules="required"
                                        v-model="payment.expiryYear"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <select
                                                v-bind="field"
                                                id="payment.expiryYear"
                                                class="form-control"
                                                name="payment.expiryYear"
                                            >
                                                <option
                                                    :value="undefined"
                                                    disabled
                                                >
                                                    Jahr des Auslaufens *
                                                </option>
                                                <option
                                                    v-for="year in expirationYears"
                                                    :value="year"
                                                    v-bind:key="year"
                                                >
                                                    {{ year }}
                                                </option>
                                            </select>
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                            </div>
                            <div
                                v-if="
                                    payment.bearer !== null &&
                                    payment.bearer.includes('Debit')
                                "
                                class="form-group row mb-0"
                            >
                                <div class="col-sm-12 col-lg-6">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.accountHolder"
                                        rules="required"
                                        v-model="payment.accountHolder"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <input
                                                v-bind="field"
                                                id="payment.accountHolder"
                                                type="text"
                                                class="form-control"
                                                name="payment.accountHolder"
                                                placeholder="Kontoinhaber *"
                                            />
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.iban"
                                        rules="required"
                                        v-model="payment.iban"
                                        v-slot="{ errorMessage, field }"
                                    >
                                        <div>
                                            <input
                                                v-bind="field"
                                                id="payment.iban"
                                                type="text"
                                                class="form-control"
                                                name="payment.iban"
                                                placeholder="IBAN *"
                                            />
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </ValidationProvider>
                                </div>
                                <div class="col-sm-12 col-lg-12">
                                    <ValidationProvider
                                        as="div"
                                        name="payment.confirmSEPADDMandate"
                                        rules="required"
                                        v-model="payment.confirmSEPADDMandate"
                                        v-slot="{ errorMessage, field, handleChange }"
                                        type="checkbox"
                                        :value="true"
                                        :unchecked-value="false"
                                    >
                                        <div>
                                            <div class="form-check my-2">
                                                <input
                                                    v-bind="field"
                                                    @input="handleChange"
                                                    id="payment.confirmSEPADDMandate"
                                                    type="checkbox"
                                                    class="form-check-input form-check-input--lg"
                                                    name="payment.confirmSEPADDMandate"
                                                    required
                                                />
                                                <label
                                                    for="payment.confirmSEPADDMandate"
                                                    class="form-check-label check-label w-auto font-weight-bold color--primary ms-3 mt-2"
                                                >
                                                    Ich stimme dem SEPA
                                                    Lastschrift-Mandat zu
                                                </label>
                                                <span
                                                    class="validation-error error-message"
                                                    >{{ errorMessage }}</span
                                                >
                                            </div>
                                        </div>
                                    </ValidationProvider>
                                </div>
                                <div
                                    class="col-sm-12 col-lg-12 color--primary mt-2"
                                >
                                    Ich ermächtige/ Wir ermächtigen <br />
                                    (A) Trost-Helden GmbH Zahlungen von meinem/
                                    unserem Konto mittels Lastschrift
                                    einzuziehen. Zugleich <br />
                                    (B) weise ich mein/ weisen wir unser
                                    Kreditinstitut an, die von Trost-Helden GmbH
                                    auf mein/ unser Konto gezogenen
                                    Lastschriften einzulösen. <br />
                                    Hinweis: Ich kann/ Wir können innerhalb von
                                    acht Wochen, beginnend mit dem
                                    Belastungsdatum, die Erstattung des
                                    belasteten Betrages verlangen. Es gelten
                                    dabei die mit meinem/ unserem Kreditinstitut
                                    vereinbarten Bedingungen.
                                </div>
                            </div>
                        </div>
                        <h3 class="my-4">Bestätigung und Bezahlung</h3>
                        <SessionProductDetails
                            v-if="product"
                            title="Dein Produkt"
                            :product="product"
                        />
                        <div class="form-group row my-4">
                            <div class="col-sm-12 col-lg-12">
                                <ValidationProvider
                                    as="div"
                                    name="accept"
                                    rules="required"
                                    v-model="accept"
                                    v-slot="{ errorMessage, field, handleChange}"
                                    type="checkbox"
                                    :value="true"
                                    :unchecked-value="false"
                                >
                                    <div>
                                        <div class="form-check my-2">
                                            <input
                                                v-bind="field"
                                                @input="handleChange"
                                                id="accept"
                                                type="checkbox"
                                                class="form-check-input form-check-input--lg"
                                                name="accept"
                                                required
                                            />
                                            <label
                                                for="accept"
                                                class="form-check-label check-label w-auto font-weight-bold color--primary ms-3"
                                            >
                                                Ich akzeptiere die
                                                <a
                                                    class="cursor-pointer dim inline-block text-link color--primary"
                                                    href="https://www.trosthelden.de/agb"
                                                    target="_blank"
                                                    >{{ "AGBs" }}</a
                                                >
                                                von TrostHelden.<br />
                                                Bitte beachte unsere
                                                <a
                                                    class="cursor-pointer dim inline-block text-link color--primary"
                                                    href="https://www.trosthelden.de/datenschutz"
                                                    target="_blank"
                                                    >{{
                                                        "Datenschutzbestimmungen"
                                                    }}</a
                                                >
                                                und die Informationen zu deinem
                                                <a
                                                    class="cursor-pointer dim inline-block text-link color--primary"
                                                    href="https://www.trosthelden.de/widerrufsbelehrung"
                                                    target="_blank"
                                                    >{{ "Widerrufsrecht" }}</a
                                                >.
                                            </label>
                                            <span
                                                class="validation-error error-message"
                                                >{{ errorMessage }}</span
                                            >
                                        </div>
                                    </div>
                                </ValidationProvider>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-12 text-center py-2">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-lg"
                                    :disabled="loading || !valid || !dirty"
                                >
                                    Jetzt zahlungspflichtig bestellen
                                    <span
                                        v-if="loading"
                                        aria-hidden="true"
                                        class="spinner-grow spinner-grow-sm mb-1"
                                        role="status"
                                    ></span>
                                </button>
                            </div>
                        </div>
                        <LoadingOverlay v-if="loading" />
                    </div>
                </ValidationObserver>
            </div>
        </div>
    </default-layout>
</template>

<script>
import { mapGetters } from "vuex";
import {
  clearLocalStore,
  getLocalStore,
  getPartnerCode,
  price as formatPrice,
  setLocalStore,
  trimData as formatTrimData,
} from "../utils/checkout";
import DefaultLayout from "../layouts/DefaultLayout";
import InformationMessage from "../components/groups/InformationMessage";
import SessionProductDetails from "../components/checkout/SessionProductDetails";
import LoadingOverlay from "../components/checkout/LoadingOverlay";
import {debugLog} from "../utils/debug";
import Repository from "../repositories/RepositoryFactory";

const PaymentRepository = Repository.get("payment");
export default {
    name: "Checkout",
    components: {
        DefaultLayout,
        InformationMessage,
        SessionProductDetails,
        LoadingOverlay,
    },
    props: ["variant_id"],
    async mounted() {
        try {
            this.loading = true;

            let Tag = await this.getSubscription();
            this.customer = { ...this.customer, Tag };

            let product = await this.getProduct();
            this.cart = { ...this.cart, PlanVariantId: this.variant_id };
            this.product = product;

            this.getCustomerData();
            this.checkCouponsAllowed();

            gtag('event', 'initiate_checkout')

            this.loading = false;
        } catch (error) {
            this.loading = false;
        }
    },
    methods: {
        async submit() {
            this.loading = true;

            try {
                this.partnerCode = await this.getPartnerCode();
            } catch (e) {
                this.partnerCode = null;
                console.log("No partner detected");
            }

            let customer = this.customer;
            let data = formatTrimData(this.payment);
            let cart = this.cart;
            let customerId = this.customerId;
            let partner = this.partnerCode;

            this.saveDataInLocalStorage();

            debugLog("submit / storedData", {
                customer: customer,
                data: data,
                cart: cart,
                customerId: customerId,
                partner: partner,
            });

            this.$store.dispatch("subscription/order", {
                cart,
                customer,
                data,
                customerId,
                partner,
            });
        },
        async successHandler(result) {

            window.dataLayer.push({
              'event': 'purchase',
              'transaction_value': this.product?.RecurringFee,
              'product_id': this.variant_id
            });

            if (!result.Url) {
                if (
                    this.isLoggedIn &&
                    this.currentUserId.toString() ===
                        this.customer.Tag.toString()
                ) {
                    await this.updateCouponStatus();
                    await this.setUserStatus();
                }

                this.$router.push(`/checkout-succeeded/${this.variant_id}`);
            } else {
                window.location.href = result.Url;
            }
        },
        errorHandler(
            err,
            message = "Die Zahlung konnte nicht verarbeitet werden.\n"
        ) {
            this.loading = false;
            message += ` ${err.errorMessage || err.message}`;
            console.error(err);
            this.$eventBus.emit("modal-requested", {
                component: InformationMessage,
                props: {
                    title: "Es ist ein Fehler aufgetreten",
                    message,
                },
            });
        },
        async updateCouponStatus() {
            let { customer, cart } = this.getDataInLocalStorage();

            if (!cart.CouponCode) return true;

            await axios.post(`/api/b2b/code/used`, {
                user: customer.Tag,
                code: cart.CouponCode,
            });
        },
        async setUserStatus() {
            try {
                let {
                    customer,
                    product,
                    payment,
                    cart,
                } = this.getDataInLocalStorage();

                this.clearDatainLocalStorage();

                let response = await axios.post(`/api/user/premium`, {
                    userId: customer.Tag,
                    email: customer.EmailAddress,
                    productVariant: product.InternalName,
                    paymentMethod: payment.bearer,
                    couponCode: cart.CouponCode,
                });

                if (response.data.success) {
                    await this.$store.dispatch("currentUser/fetch");
                } else {
                    this.errorHandler(
                        response,
                        "Ihr Benutzer konnte nicht auf Premium hochgestuft werden"
                    );
                }
            } catch (error) {
                this.errorHandler(
                    error,
                    "Ihr Benutzer konnte nicht auf Premium hochgestuft werden"
                );
                return Promise.reject(error);
            }
        },
        checkCoupon() {
            this.couponHandler = {
                loading: true,
                message: null,
                error: false,
            };

            let cart = this.cart;
            let customer = this.customer;
            let callback = this.couponMessage;

            this.$store.dispatch("subscription/preview", {
                cart,
                customer,
                callback,
            });
        },
        couponMessage({ Order: { Coupon, Total } }) {
            let { message, error, loading } = this.couponHandler;
            if (Coupon.AppliesToCart) {
                this.product = {
                    ...this.product,
                    RecurringFee: formatPrice(Total),
                };
                message =
                    "Dein Coupon-Code ist gültig. Der Rabatt wurde abgezogen.";
            } else {
                this.cart = { ...this.cart, CouponCode: null };
                message =
                    "Dein Coupon-Code ist leider nicht gültig. Bitte überprüfe deine Eingabe.";
                error = true;
            }
            loading = false;
            this.couponHandler = { message, error, loading };
        },
        async getSubscription() {
            try {
                let tag = await this.$store.dispatch("subscription/init", {
                    methods: Object.keys(this.methods),
                    success: this.successHandler,
                    error: this.errorHandler,
                });
                return tag;
            } catch (error) {
                return Promise.reject(error);
            }
        },
        async getProduct() {
          try {
            let {result} = await PaymentRepository.isStandardProduct(this.variant_id);

            if (result) {
              return await this.$store.dispatch(
                  "subscription/product",
                  this.variant_id
              );
            } else {
              if (this.isLoggedIn) {
                this.$router.push({
                  name: "dashboard",
                });
              } else {
                window.location.href = this.urlExternalApp;
              }
            }
          } catch (e) {
            console.error(e)
          }
        },
        async getCustomerData() {
            try {
                if (
                    !this.isLoggedIn ||
                    !this.customer.Tag ||
                    this.customer.Tag?.startsWith("coupon-")
                )
                    return false;

                let {
                    customer,
                } = await this.$store.dispatch("subscription/customerData", {
                    userId: this.customer.Tag,
                });
                if (customer) {
                    let {
                        FirstName,
                        LastName,
                        EmailAddress,
                        PhoneNumber,
                        Address,
                    } = customer;

                    this.customerId = customer?.Id ?? null;

                    this.customer = {
                        ...this.customer,
                        FirstName,
                        LastName,
                        EmailAddress,
                        PhoneNumber,
                        Address,
                    };
                }
            } catch (error) {
                return Promise.reject(error);
            }
        },
        async getPartnerCode() {
            return await getPartnerCode(
                this.customer.Tag,
                this.cart.CouponCode
            );
        },
        saveDataInLocalStorage() {
            setLocalStore("customer", JSON.stringify(this.customer));
            setLocalStore("product", JSON.stringify(this.product));
            setLocalStore("payment", JSON.stringify(this.payment));
            setLocalStore("cart", JSON.stringify(this.cart));
            setLocalStore("partner", this.partnerCode);
        },
        clearDatainLocalStorage() {
            clearLocalStore("customer");
            clearLocalStore("product");
            clearLocalStore("payment");
            clearLocalStore("cart");
            clearLocalStore("partner");
        },
        getDataInLocalStorage() {
            let customer = getLocalStore("customer");
            let product = getLocalStore("product");
            let payment = getLocalStore("payment");
            let cart = getLocalStore("cart");
            let partner = getLocalStore("partner");

            const data = {
                customer: JSON.parse(customer) ?? null,
                product: JSON.parse(product) ?? null,
                payment: JSON.parse(payment) ?? null,
                cart: JSON.parse(cart) ?? null,
                partner: partner ?? null,
            };

            debugLog("localStorage Data", data);

            return data;
        },
      async checkCouponsAllowed() {
          try {
            let {result} = await PaymentRepository.isCouponProduct(this.variant_id);
            this.couponsAllowed = !result;
          } catch (e) {
            console.error(e)
          }
      },
    },
    computed: {
        ...mapGetters("currentUser", {
            currentUserId: "getId",
            isVerified: "isVerified",
            hasCompletedFrabo: "hasCompletedFrabo",
            isLoggedIn: "isLoggedIn",
            currentUserPartnerId: "getPartnerId",
        }),
        ...mapGetters("subscription", {
            urlExternalApp: "getUrlExternalApp",
            countries: "getCountries",
            expirationYears: "getExpirationYears",
            availableMethods: "getPaymentMethods",
            signupService: "getSignupService",
            paymentService: "getPaymentService",
        }),

        bearer() {
            return this.payment.bearer;
        },
    },
    watch: {
        bearer(value, oldValue) {
            if (oldValue !== null && oldValue !== value) {
                this.payment = { bearer: value };
            }
        }
    },
    data() {
        return {
            partnerCode: null,
            tag: null,
            error: false,
            loading: false,
            accept: false,
            product: null,
            hasCoupon: false,
            couponsAllowed: false,
            couponHandler: {
                loading: false,
                message: null,
                error: false,
            },
            cart: {
                PlanVariantId: null,
                CouponCode: null,
            },
            customerId: null,
            customer: {
                FirstName: null,
                LastName: null,
                EmailAddress: null,
                PhoneNumber: null,
                Address: {
                    Street: null,
                    HouseNumber: null,
                    PostalCode: null,
                    City: null,
                    Country: "DE",
                },
                CustomFields: {
                    Gender: null,
                },
            },
            payment: {
                bearer: null,
            },
            methods: {
                Debit: {
                    name: "Lastschrift",
                    images: ["Debit"],
                },
                CreditCard: {
                    name: "Kreditkarte",
                    images: [
                        "Visa",
                        "Mastercard",
                        "Amex",
                        "UnionPay",
                        "JCB",
                        "Diners",
                    ],
                },
                PayPal: {
                    name: "PayPal",
                    images: ["PayPal"],
                },
            },
            genres: [
                { code: "Herr", name: "Herr" },
                { code: "Frau", name: "Frau" },
            ],
        };
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.form-check-input--lg {
    width: 1.5rem;
    height: 1.5rem;
}

.text-link {
    text-decoration: underline;

    &:hover {
        color: $brand-color-primary;
        opacity: 0.6;
    }
}

.form-coupon {
    justify-content: space-between;

    &-input {
        flex: 1;
    }

    &-button {
    }
}

.form-payment-method {
    display: flex;
    flex-wrap: nowrap;

    .form-check-input {
        position: relative;
        margin-left: 0;
    }
}
</style>
