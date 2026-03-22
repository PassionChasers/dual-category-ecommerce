@extends('layouts.user.app')

@section('title', 'Privacy Policy | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6 md:px-20">
    <!-- Header -->
    <div class="max-w-5xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-600">Privacy Policy</h1>
        <p class="text-gray-600 max-w-3xl mx-auto">
            Your privacy matters to us. This policy explains how {{ $settings->app_name ?? 'our ecommerce platform' }}
            collects, uses, shares, and protects your information when you browse our site or place an order.
        </p>
    </div>

    <!-- Policy Content -->
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-10 rounded-2xl shadow-lg border border-gray-100 space-y-8 text-gray-700 leading-relaxed">
        <!-- 1. Information We Collect -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">1. Information We Collect</h2>
            <p class="mb-3">
                We collect the information necessary to provide you with a secure and convenient shopping experience.
                This may include:
            </p>
            <ul class="list-disc list-inside space-y-1 text-sm md:text-base">
                <li><span class="font-medium">Account details:</span> name, email address, phone number, password.</li>
                <li><span class="font-medium">Order information:</span> delivery address, billing details, items purchased, order history.</li>
                <li><span class="font-medium">Technical data:</span> IP address, browser type, device information, and usage logs.</li>
                <li><span class="font-medium">Communication data:</span> messages sent through contact forms, support requests, and feedback.</li>
            </ul>
        </div>

        <!-- 2. How We Use Your Data -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">2. How We Use Your Data</h2>
            <p class="mb-3">
                We use your information only for legitimate purposes related to operating
                {{ $settings->app_name ?? 'our platform' }}, such as:
            </p>
            <ul class="list-disc list-inside space-y-1 text-sm md:text-base">
                <li>Processing and delivering your orders.</li>
                <li>Creating and managing your customer account.</li>
                <li>Providing customer support and responding to your questions.</li>
                <li>Sending order confirmations, delivery updates, and important service notifications.</li>
                <li>Improving our website, services, and user experience based on aggregated analytics.</li>
                <li>Sending promotional offers or newsletters (only where permitted and you can opt out anytime).</li>
            </ul>
        </div>

        <!-- 3. Legal Basis & Sharing of Data -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">3. Legal Basis &amp; Sharing of Data</h2>
            <p class="mb-3">
                We process your data based on your consent, our contractual obligations (e.g., fulfilling an order),
                and our legitimate interest in operating a secure ecommerce platform.
            </p>
            <p class="mb-2">
                We may share your data with trusted third parties only when necessary, such as:
            </p>
            <ul class="list-disc list-inside space-y-1 text-sm md:text-base">
                <li>Delivery and logistics partners to deliver your orders.</li>
                <li>Payment service providers to process secure payments.</li>
                <li>IT and hosting providers who support our infrastructure.</li>
                <li>Authorities, if required by law or to protect our legal rights.</li>
            </ul>
            <p class="mt-3">
                We do <span class="font-semibold">not</span> sell your personal information to third parties.
            </p>
        </div>

        <!-- 4. Data Protection & Retention -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">4. Data Protection &amp; Retention</h2>
            <p class="mb-3">
                We use technical and organizational measures to protect your data, including encryption,
                access controls, secure servers, and regular monitoring.
            </p>
            <p>
                We keep your information only as long as necessary for the purposes described in this policy,
                such as fulfilling your orders, complying with legal obligations, and resolving disputes.
            </p>
        </div>

        <!-- 5. Cookies & Tracking Technologies -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">5. Cookies &amp; Tracking Technologies</h2>
            <p class="mb-3">
                {{ $settings->app_name ?? 'Our site' }} uses cookies and similar technologies to:
            </p>
            <ul class="list-disc list-inside space-y-1 text-sm md:text-base">
                <li>Remember your preferences and items in your cart.</li>
                <li>Keep you logged in securely.</li>
                <li>Understand how visitors use our website to improve performance.</li>
            </ul>
            <p class="mt-3">
                You can control or disable cookies through your browser settings, but some features of the website may
                not function properly if cookies are disabled.
            </p>
        </div>

        <!-- 6. Your Rights -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">6. Your Rights</h2>
            <p class="mb-3">
                Depending on your local laws, you may have the right to:
            </p>
            <ul class="list-disc list-inside space-y-1 text-sm md:text-base">
                <li>Access the personal data we hold about you.</li>
                <li>Request corrections to inaccurate or incomplete data.</li>
                <li>Request deletion of your data, where applicable.</li>
                <li>Object to or restrict certain types of data processing.</li>
                <li>Withdraw consent for marketing communications at any time.</li>
            </ul>
            <p class="mt-3">
                To exercise these rights, please contact us using the details below.
            </p>
        </div>

        <!-- 7. Updates to This Policy -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">7. Updates to This Policy</h2>
            <p>
                We may update this Privacy Policy from time to time to reflect changes in our services, legal
                requirements, or security practices. When we do, we will update the “Last Updated” date and, where
                appropriate, notify you through the website or by email.
            </p>
        </div>

        <!-- 8. Contact Us -->
        <div>
            <h2 class="text-xl font-semibold text-primary-600 mb-2">8. Contact Us</h2>
            <p class="mb-3">
                If you have any questions or concerns about this Privacy Policy or how
                {{ $settings->app_name ?? 'our platform' }} handles your data, please contact us at:
            </p>
            <div class="space-y-1 text-sm md:text-base">
                <p>
                    <i class="fas fa-envelope text-primary-500 mr-2"></i>
                    {{ $settings->support_email ?? 'support@example.com' }}
                </p>
                <p>
                    <i class="fas fa-phone text-primary-500 mr-2"></i>
                    {{ $settings->support_phone ?? '+977-0000000000' }}
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
