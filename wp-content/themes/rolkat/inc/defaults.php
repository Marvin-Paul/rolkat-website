<?php

if (!defined('ABSPATH')) {
    exit;
}

function rolkat_default_phone()
{
    return '+256 787 165 366';
}

function rolkat_default_email()
{
    return 'katongolejames122@gmail.com';
}

function rolkat_default_address()
{
    return "Hanora Plaza, Zana, Entebbe Road\nOpposite Be Energies";
}

function rolkat_default_hours()
{
    return 'Monday – Friday: 8:00 AM – 5:30 PM · Saturday: 9:00 AM – 2:00 PM';
}

function rolkat_default_whatsapp()
{
    return '+256787165366';
}

function rolkat_default_map_embed()
{
    return 'https://www.google.com/maps?q=Hanora+Plaza+Zana+Entebbe+Road+Uganda&output=embed';
}

function rolkat_get_phone()
{
    $value = get_theme_mod('rolkat_phone', '');
    return $value !== '' ? $value : rolkat_default_phone();
}

function rolkat_get_email()
{
    $value = get_theme_mod('rolkat_email', '');
    return $value !== '' ? $value : rolkat_default_email();
}

function rolkat_get_address()
{
    $value = get_theme_mod('rolkat_address', '');
    return $value !== '' ? $value : rolkat_default_address();
}

function rolkat_get_hours()
{
    $value = get_theme_mod('rolkat_hours', '');
    return $value !== '' ? $value : rolkat_default_hours();
}

function rolkat_get_whatsapp()
{
    $value = get_theme_mod('rolkat_whatsapp', '');
    return $value !== '' ? $value : rolkat_default_whatsapp();
}

function rolkat_get_map_embed()
{
    $value = get_theme_mod('rolkat_map_embed', '');
    return $value !== '' ? $value : rolkat_default_map_embed();
}

function rolkat_whatsapp_url()
{
    $digits = preg_replace('/[^0-9]/', '', rolkat_get_whatsapp());
    return $digits !== '' ? 'https://wa.me/' . $digits : '';
}

function rolkat_tel_href($phone)
{
    return 'tel:' . preg_replace('/[^0-9+]/', '', $phone);
}

function rolkat_property_statuses()
{
    return array(
        'Available' => __('Available', 'rolkat'),
        'Sold' => __('Sold', 'rolkat'),
        'Rented' => __('Rented', 'rolkat'),
    );
}

function rolkat_property_types()
{
    return array(
        'Sale' => __('For sale', 'rolkat'),
        'Rent' => __('For rent', 'rolkat'),
        'Land' => __('Land / plot', 'rolkat'),
    );
}

function rolkat_service_categories()
{
    return array(
        'loans' => array(
            'label' => __('Loans', 'rolkat'),
            'summary' => __('Fast, fair and flexible credit for daily earners, market vendors, groups and small businesses — with a 24-hour approval promise.', 'rolkat'),
            'audience' => __('Boda boda cyclists, traders, market vendors, groups and SMEs', 'rolkat'),
        ),
        'property-management' => array(
            'label' => __('Property Management', 'rolkat'),
            'summary' => __('Professional care for landlords: tenant screening, rent collection, maintenance and diaspora oversight.', 'rolkat'),
            'audience' => __('Property owners and landlords, including absentee and diaspora clients', 'rolkat'),
        ),
        'real-estate' => array(
            'label' => __('Real Estate', 'rolkat'),
            'summary' => __('Sales, leasing, land deals and title due diligence so buyers and sellers can move with confidence.', 'rolkat'),
            'audience' => __('Buyers, sellers, landlords and tenants', 'rolkat'),
        ),
    );
}

function rolkat_trust_points()
{
    return array(
        array(
            'title' => __('24-hour decisions', 'rolkat'),
            'text' => __('Quick credit decisions without the delays of a traditional bank.', 'rolkat'),
        ),
        array(
            'title' => __('Transparent terms', 'rolkat'),
            'text' => __('Clear pricing and respectful collection aligned with Ugandan microfinance rules.', 'rolkat'),
        ),
        array(
            'title' => __('Three services, one team', 'rolkat'),
            'text' => __('Loans, property management and real estate under one trusted roof.', 'rolkat'),
        ),
        array(
            'title' => __('Local and approachable', 'rolkat'),
            'text' => __('Based on Entebbe Road in Zana, serving everyday Ugandans and small businesses.', 'rolkat'),
        ),
    );
}

function rolkat_default_services()
{
    return array(
        array(
            'number' => '01',
            'title' => __('Micro Loans', 'rolkat'),
            'category' => 'loans',
            'description' => __('Fast, flexible small credit for boda boda cyclists, small traders, and daily earners with daily or weekly repayment options and 24-hour approval.', 'rolkat'),
        ),
        array(
            'number' => '02',
            'title' => __('Small Business Loans', 'rolkat'),
            'category' => 'loans',
            'description' => __('Working capital and modest expansion financing for retail shops, kiosks, and established enterprises assessed on real cash-flow.', 'rolkat'),
        ),
        array(
            'number' => '03',
            'title' => __('Group Loans', 'rolkat'),
            'category' => 'loans',
            'description' => __('Solidarity lending for trader groups, market associations, and boda boda stages with mutual guarantees and weekly collections.', 'rolkat'),
        ),
        array(
            'number' => '04',
            'title' => __('Emergency Loans', 'rolkat'),
            'category' => 'loans',
            'description' => __('Same-day rapid credit for unexpected medical expenses, motorcycle repairs, or time-sensitive inventory emergencies.', 'rolkat'),
        ),
        array(
            'number' => '05',
            'title' => __('Market Vendor Loans', 'rolkat'),
            'category' => 'loans',
            'description' => __('Tailored daily working capital for fresh food sellers and market stallholders to buy morning stock and repay each evening.', 'rolkat'),
        ),
        array(
            'number' => '06',
            'title' => __('Tenant Sourcing & Screening', 'rolkat'),
            'category' => 'property-management',
            'description' => __('Comprehensive vetting of tenant National IDs, background references, and income viability compliant with the Landlord and Tenant Act, 2022.', 'rolkat'),
        ),
        array(
            'number' => '07',
            'title' => __('Rent Collection & Remittance', 'rolkat'),
            'category' => 'property-management',
            'description' => __('Disciplined rent collection on due dates, official electronic receipting, arrears follow-up, and timely net remittances to owners.', 'rolkat'),
        ),
        array(
            'number' => '08',
            'title' => __('Property Maintenance & Repairs', 'rolkat'),
            'category' => 'property-management',
            'description' => __('Routine upkeep and prompt coordination of emergency repairs (plumbing, electrical, structural) using thoroughly vetted contractors.', 'rolkat'),
        ),
        array(
            'number' => '09',
            'title' => __('Diaspora & Absentee Landlord Oversight', 'rolkat'),
            'category' => 'property-management',
            'description' => __('Dedicated caretaking, condition inspections with photographic reports, utility tracking, and transparent monthly accounting for owners living abroad.', 'rolkat'),
        ),
        array(
            'number' => '10',
            'title' => __('Land & Plot Sales', 'rolkat'),
            'category' => 'real-estate',
            'description' => __('Connecting buyers and sellers with verified residential and commercial plots across Mailo, Freehold, and Leasehold land tenures.', 'rolkat'),
        ),
        array(
            'number' => '11',
            'title' => __('Property Sales & Leasing', 'rolkat'),
            'category' => 'real-estate',
            'description' => __('Professional listing, advertising, and negotiation for residential homes, apartment blocks, commercial shops, and office suites.', 'rolkat'),
        ),
        array(
            'number' => '12',
            'title' => __('Title Search & Due Diligence', 'rolkat'),
            'category' => 'real-estate',
            'description' => __('Rigorous land registry searches, boundary surveys, encumbrance verifications, and legal support to safeguard clients against property fraud.', 'rolkat'),
        ),
    );
}
