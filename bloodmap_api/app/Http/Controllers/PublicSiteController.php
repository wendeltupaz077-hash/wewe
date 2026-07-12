<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        $stats = [
            'facilities' => Facility::count() ?: 312,
            'active_requests' => EmergencyRequest::where('status', '!=', 'resolved')->count() ?: 27,
            'donors' => Donor::count() ?: 48900,
            'emergency_ready' => '99.2%',
        ];

        return view('public.home', compact('stats'));
    }

    public function donorDirectory(): View
    {
        $facilities = [
            ['name' => 'PRC National Blood Center', 'city' => 'Manila', 'types' => 'B+, A+, B-, AB+', 'status' => 'stocked', 'status_label' => 'Stocked'],
            ['name' => 'Quezon City General Hospital', 'city' => 'Quezon City', 'types' => 'O-, A-, B+', 'status' => 'emergency', 'status_label' => 'Emergency Need'],
            ['name' => 'Cebu Chapter Blood Bank', 'city' => 'Cebu City', 'types' => 'O+, AB-, A+', 'status' => 'stocked', 'status_label' => 'Stocked'],
            ['name' => 'Davao Regional Blood Center', 'city' => 'Davao City', 'types' => 'B+, O+, A-', 'status' => 'emergency', 'status_label' => 'Emergency Need'],
            ['name' => 'Makati Medical Center', 'city' => 'Makati', 'types' => 'O+, A+, AB+', 'status' => 'stocked', 'status_label' => 'Stocked'],
            ['name' => 'Iloilo Provincial Blood Bank', 'city' => 'Iloilo City', 'types' => 'O-, B-, A+', 'status' => 'closed', 'status_label' => 'Closed'],
            ['name' => 'Baguio General Hospital', 'city' => 'Baguio City', 'types' => 'O+, A+, B-', 'status' => 'stocked', 'status_label' => 'Stocked'],
            ['name' => 'Zamboanga City Blood Center', 'city' => 'Zamboanga City', 'types' => 'O+, AB+, B+', 'status' => 'emergency', 'status_label' => 'Emergency Need'],
        ];

        return view('public.donor-directory', compact('facilities'));
    }

    public function about(): View
    {
        $timeline = [
            ['title' => 'The Why', 'text' => 'Every year, thousands of Filipinos face delays finding compatible blood during emergencies. SmartBlood PH exists to close that gap with real-time visibility.'],
            ['title' => 'The Who', 'text' => 'Built by a small team of health-tech volunteers, in partnership with the Philippine Red Cross chapters and regional hospitals nationwide.'],
            ['title' => 'The How', 'text' => 'We connect facility inventories, donor registries, and emergency escalation paths into a single living network — updated in real time.'],
        ];

        $team = [
            ['name' => 'Dr. Elena Marquez', 'role' => 'Medical Director'],
            ['name' => 'Rafael Santos', 'role' => 'Platform Lead'],
            ['name' => 'Mika Villanueva', 'role' => 'Partnerships'],
        ];

        return view('public.about', compact('timeline', 'team'));
    }

    public function faq(): View
    {
        $faqs = [
            'Eligibility' => [
                ['q' => 'Who can donate blood?', 'a' => 'Most healthy individuals aged 16-65, weighing at least 50kg, can donate. A quick screening at the facility confirms your eligibility on the day.'],
                ['q' => 'How often can I donate?', 'a' => 'Whole blood donors can donate every 12 weeks (about 3 months) to allow the body to fully replenish.'],
                ['q' => 'Can I donate if I have a cold?', 'a' => 'It\'s best to wait until you\'re fully recovered and symptom-free for at least 48 hours before donating.'],
            ],
            'Safety' => [
                ['q' => 'Is donating blood safe?', 'a' => 'Yes. All equipment is sterile and single-use. Trained medical staff handle every donation following DOH and PRC safety protocols.'],
                ['q' => 'Will donating weaken me?', 'a' => 'Most donors feel normal within a day. You\'ll be given fluids and a snack afterward, and mild rest is recommended.'],
                ['q' => 'Is my donated blood tested?', 'a' => 'Every unit is screened for infectious diseases before being released to any facility.'],
            ],
            'Process' => [
                ['q' => 'How long does donation take?', 'a' => 'The full visit takes about 45-60 minutes; the actual blood draw takes only 8-10 minutes.'],
                ['q' => 'What should I do before donating?', 'a' => 'Eat a healthy meal, stay hydrated, and get good sleep the night before your appointment.'],
                ['q' => 'How do I find a donation center?', 'a' => 'Use our Donor Directory page to search and filter partner facilities near you.'],
            ],
        ];

        return view('public.faq', compact('faqs'));
    }

    public function blog(): View
    {
        $categories = ['All', 'Health', 'Wellness', 'Donation Tips'];

        $posts = [
            ['title' => 'Why Regular Blood Donation Improves Your Own Health', 'category' => 'Wellness', 'date' => 'Jun 12, 2026', 'excerpt' => 'Beyond saving lives, donating blood regularly can support cardiovascular health and encourage new blood cell production.'],
            ['title' => '5 Myths About Blood Donation, Debunked', 'category' => 'Health', 'date' => 'May 28, 2026', 'excerpt' => 'From needle fear to weight loss myths — we separate fact from fiction on what really happens when you donate.'],
            ['title' => 'How to Prepare the Night Before You Donate', 'category' => 'Donation Tips', 'date' => 'May 14, 2026', 'excerpt' => 'Simple steps like hydration, sleep, and iron-rich meals make your donation experience smoother and safer.'],
            ['title' => 'Understanding Blood Types and Compatibility', 'category' => 'Health', 'date' => 'Apr 30, 2026', 'excerpt' => 'A quick guide to the ABO and Rh systems, and why O-negative donors are considered universal givers.'],
            ['title' => 'The Emotional Impact of Being a Regular Donor', 'category' => 'Wellness', 'date' => 'Apr 18, 2026', 'excerpt' => 'Donors share how giving blood consistently has shaped their sense of purpose and community.'],
            ['title' => 'What Happens to Your Blood After You Donate?', 'category' => 'Donation Tips', 'date' => 'Apr 2, 2026', 'excerpt' => 'From the moment your donation is collected to when it reaches a patient — the full journey explained.'],
        ];

        $featured = $posts[0];
        $rest = array_slice($posts, 1);

        return view('public.blog', compact('categories', 'posts', 'featured', 'rest'));
    }

    public function features(): View
    {
        return view('public.features');
    }

    public function howItWorks(): View
    {
        return view('public.how-it-works');
    }

    public function stockStatus(): View
    {
        $facilities = Facility::query()
            ->with(['inventory' => fn ($q) => $q->where('status', 'available')
                ->where('expiry_date', '>=', now()->toDateString())])
            ->orderBy('name')
            ->get()
            ->map(function (Facility $f) {
                $f->computed_status = $f->computeStockStatus();
                $f->total_units = $f->availableUnits();

                return $f;
            });

        return view('public.stock-status', compact('facilities'));
    }

    public function download(): View
    {
        return view('public.download');
    }

    public function mobileApp(): View
    {
        return view('public.mobile-app');
    }

    public function contact(): View
    {
        return view('public.contact');
    }
}
