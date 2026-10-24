<?php
include("db_conn.php");
ini_set("display_errors", 1);

include("db_conn.php");
ini_set("display_errors", 1);

// 1. CHECK IF A SCHOOL IS ALREADY REGISTERED
$check_query = "SELECT * FROM settings LIMIT 1";
$check_result = mysqli_query($conn, $check_query);

if ($check_result && mysqli_num_rows($check_result) > 0) {
    // A school is already registered! Redirect them immediately.
    header("Location: ../index.php");
    exit();
}

$success_message = "";

if (isset($_REQUEST['register'])) {
    $school_name = mysqli_real_escape_string($conn, $_REQUEST['school_name']);
    $short_name = mysqli_real_escape_string($conn, $_REQUEST['short_name']);
    $motto = mysqli_real_escape_string($conn, $_REQUEST['motto']);
    $address = mysqli_real_escape_string($conn, $_REQUEST['address']);
    $email = mysqli_real_escape_string($conn, $_REQUEST['email']);
    $website = mysqli_real_escape_string($conn, $_REQUEST['website']);
    $phone = mysqli_real_escape_string($conn, $_REQUEST['phone']);
    $phone2 = mysqli_real_escape_string($conn, $_REQUEST['phone2']);
    $current_session = mysqli_real_escape_string($conn, $_REQUEST['current_session']);
    $current_term = mysqli_real_escape_string($conn, $_REQUEST['current_term']);
 
    $logo = $_FILES["logo"]["name"];
    $tmp_name = $_FILES["logo"]["tmp_name"];
    $target = "logo/" . basename($logo);

    // Using your original logic with correct parameters
    if (move_uploaded_file($tmp_name, $target)) {
       $sql = "INSERT INTO settings(school_name, short_name, motto, logo, address, email, phone, phone2, website, current_session, current_term) VALUES ('$school_name', '$short_name', '$motto', '$logo', '$address', '$email', '$phone', '$phone2', '$website', '$current_session', '$current_term')";

       $result = mysqli_query($conn, $sql);

       if ($result) {
         $success_message = $school_name;
       } else {
         echo "<script>alert('Database Error: " . mysqli_error($conn) . "');</script>";
       }
    } else {
        // Fallback if logo upload is optional or failed
        $sql = "INSERT INTO settings(school_name, short_name, motto, logo, address, email, phone, phone2, website, current_session, current_term) VALUES ('$school_name', '$short_name', '$motto', '', '$address', '$email', '$phone', '$phone2', '$website', '$current_session', '$current_term')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $success_message = $school_name;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>School Onboarding & Registration Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .step-section {
            transition: opacity 0.35s ease, transform 0.35s ease;
        }

        .step-hidden {
            opacity: 0;
            transform: translateY(12px);
            pointer-events: none;
            position: absolute;
            width: 100%;
            visibility: hidden;
        }

        .step-active {
            opacity: 1;
            transform: translateY(0);
            position: relative;
            visibility: visible;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }

        .animate-float {
            animation: floatSlow 4s ease-in-out infinite;
        }

        @keyframes dropRibbon {
            0% { opacity: 0; transform: translateY(-50px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .animate-drop-ribbon {
            animation: dropRibbon 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes floatBalloon {
            0% { transform: translateY(20px) scale(0.8); opacity: 0; }
            50% { opacity: 0.8; }
            100% { transform: translateY(-80px) scale(1.1); opacity: 0; }
        }

        .balloon-1 { animation: floatBalloon 3s ease-in-out infinite; }
        .balloon-2 { animation: floatBalloon 3.5s ease-in-out 0.8s infinite; }
        .balloon-3 { animation: floatBalloon 2.8s ease-in-out 1.5s infinite; }
    </style>
</head>

<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">

    <!-- Top Navbar -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-cyan-400 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight text-white">EduCore <span class="text-indigo-400 font-normal text-sm">Portal</span></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            <!-- Left Column: Multi-Step Form -->
            <div class="lg:col-span-7 bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">

                <!-- Step Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-between text-xs font-medium text-slate-400 mb-3">
                        <span id="stepIndicator1" class="text-indigo-400 font-semibold">1. Identity & Logo</span>
                        <span id="stepIndicator2">2. Contact & Location</span>
                        <span id="stepIndicator3">3. Academic Session</span>
                    </div>
                    <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-gradient-to-r from-indigo-500 to-cyan-400 w-1/3 transition-all duration-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Form -->
                <form id="registrationForm" method="POST" enctype="multipart/form-data" class="relative">

                    <!-- STEP 1: School Identity -->
                    <div id="step1" class="step-section step-active space-y-5">
                        <div>
                            <h2 class="text-xl font-bold text-white">School Identity</h2>
                            <p class="text-sm text-slate-400">Basic registration details and official branding.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- school_name -->
                            <div class="sm:col-span-2">
                                <label for="school_name" class="block text-xs font-medium text-slate-300 mb-1">Full School Name <span class="text-rose-400">*</span></label>
                                <input type="text" id="school_name" name="school_name" required placeholder="e.g. Jostech Model College"
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                            </div>

                            <!-- short_name -->
                            <div>
                                <label for="short_name" class="block text-xs font-medium text-slate-300 mb-1">Short Name / Acronym <span class="text-rose-400">*</span></label>
                                <input type="text" id="short_name" name="short_name" required placeholder="e.g. JOSH"
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                            </div>
                        </div>

                        <!-- motto -->
                        <div>
                            <label for="motto" class="block text-xs font-medium text-slate-300 mb-1">School Motto <span class="text-rose-400">*</span></label>
                            <input type="text" id="motto" name="motto" required placeholder="e.g. Turning Ideas Into Functional System"
                                class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                        </div>

                        <!-- logo -->
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">School Logo</label>
                            <label for="logo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-800 hover:border-indigo-500 rounded-xl cursor-pointer bg-slate-950/50 hover:bg-slate-900/60 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <svg class="w-7 h-7 mb-2 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <p class="text-xs text-slate-300"><span class="font-semibold text-indigo-400">Click to upload logo</span> or drag and drop</p>
                                    <p id="logoFileName" class="text-[11px] text-slate-500 mt-1">PNG, JPG, SVG (Max 2MB)</p>
                                </div>
                                <input id="logo" name="logo" type="file" accept="image/*" class="hidden" />
                            </label>
                        </div>
                    </div>

                    <!-- STEP 2: Location & Contact -->
                    <div id="step2" class="step-section step-hidden space-y-5">
                        <div>
                            <h2 class="text-xl font-bold text-white">Location & Contact Details</h2>
                            <p class="text-sm text-slate-400">How parents, students, and administrators can reach the school.</p>
                        </div>

                        <!-- address -->
                        <div>
                            <label for="address" class="block text-xs font-medium text-slate-300 mb-1">Physical Address <span class="text-rose-400">*</span></label>
                            <textarea id="address" name="address" rows="2" required placeholder="Street address, city, state..."
                                class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- email -->
                            <div>
                                <label for="email" class="block text-xs font-medium text-slate-300 mb-1">Official Email <span class="text-rose-400">*</span></label>
                                <input type="email" id="email" name="email" required placeholder="info@school.edu"
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                            </div>

                            <!-- website -->
                            <div>
                                <label for="website" class="block text-xs font-medium text-slate-300 mb-1">Website URL</label>
                                <input type="url" id="website" name="website" placeholder="https://www.school.edu"
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- phone -->
                            <div>
                                <label for="phone" class="block text-xs font-medium text-slate-300 mb-1">Primary Phone <span class="text-rose-400">*</span></label>
                                <input type="tel" id="phone" name="phone" required placeholder="0703 000 0000"
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                            </div>

                            <!-- phone2 -->
                            <div>
                                <label for="phone2" class="block text-xs font-medium text-slate-300 mb-1">Secondary Phone (Optional)</label>
                                <input type="tel" id="phone2" name="phone2" placeholder="+234 800 000 0001"
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Academic Calendar -->
                    <div id="step3" class="step-section step-hidden space-y-5">
                        <div>
                            <h2 class="text-xl font-bold text-white">Academic Configuration</h2>
                            <p class="text-sm text-slate-400">Set the active academic session and current term.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- current_session -->
                            <div>
                                <label for="current_session" class="block text-xs font-medium text-slate-300 mb-1">Current Session <span class="text-rose-400">*</span></label>
                                <input type="text" id="current_session" name="current_session" required placeholder="e.g. 2026/2027"
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition" />
                            </div>

                            <!-- current_term -->
                            <div>
                                <label for="current_term" class="block text-xs font-medium text-slate-300 mb-1">Current Term <span class="text-rose-400">*</span></label>
                                <select id="current_term" name="current_term" required
                                    class="w-full rounded-lg bg-slate-950 border border-slate-800 px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                                    <option value="" disabled selected>Select Term</option>
                                    <option value="First Term">First Term</option>
                                    <option value="Second Term">Second Term</option>
                                    <option value="Third Term">Third Term</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 bg-indigo-950/30 border border-indigo-500/20 rounded-xl p-4 flex items-start space-x-3">
                            <svg class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Please review the <strong>Live Profile Preview</strong> card to verify your school details before completing registration.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-8 pt-5 border-t border-slate-800 flex items-center justify-between">
                        <button type="button" id="prevBtn"
                            class="hidden px-5 py-2.5 rounded-lg text-sm font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 transition">
                            ← Back
                        </button>
                        <div class="ml-auto flex items-center space-x-3">
                            <button type="button" id="nextBtn"
                                class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition">
                                Continue →
                            </button>
                            <!-- Submit button triggers your PHP register variable -->
                            <button type="submit" id="submitBtn" name="register"
                                class="hidden px-6 py-2.5 rounded-lg text-sm font-semibold bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 shadow-lg shadow-emerald-500/20 transition flex items-center space-x-2">
                                <span id="submitText">Complete Registration ✓</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Right Column: Live Interactive School Preview Card -->
            <div class="lg:col-span-5 sticky top-24">
                <div class="bg-gradient-to-b from-slate-900 to-slate-950 border border-slate-800 rounded-2xl p-6 shadow-2xl animate-float">
                    <div class="flex items-center justify-between border-b border-slate-800/80 pb-4 mb-5">
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Live Profile Preview</span>
                        <span id="previewBadge" class="text-xs font-mono px-2.5 py-1 rounded-md bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                            SCH
                        </span>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div id="previewLogoContainer" class="w-16 h-16 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center overflow-hidden shrink-0">
                            <span id="previewAcronym" class="text-lg font-bold text-indigo-400">SCH</span>
                            <img id="previewLogoImg" src="" alt="Logo" class="hidden w-full h-full object-cover" />
                        </div>
                        <div>
                            <h3 id="previewSchoolName" class="text-lg font-bold text-white leading-snug">Your School Name</h3>
                            <p id="previewMotto" class="text-xs italic text-slate-400 mt-0.5">"School motto will appear here"</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3 text-xs text-slate-300 border-t border-slate-800/80 pt-5">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Address:</span>
                            <span id="previewAddress" class="text-right font-medium max-w-[65%] truncate">Not provided</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Email:</span>
                            <span id="previewEmail" class="font-medium">Not provided</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Phones:</span>
                            <span id="previewPhones" class="font-medium">Not provided</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Website:</span>
                            <span id="previewWebsite" class="font-medium text-indigo-400">---</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-slate-500 block">Academic Session</span>
                            <span id="previewSession" class="text-xs font-semibold text-white">----/----</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] text-slate-500 block">Active Term</span>
                            <span id="previewTerm" class="inline-block mt-0.5 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Unselected
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Dropping Ribbon & Balloon Success Modal (Automatically triggered if PHP inserts successfully) -->
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-md <?php echo !empty($success_message) ? '' : 'hidden'; ?> px-4">
        <div class="bg-gradient-to-b from-slate-900 to-slate-950 border border-emerald-500/30 rounded-3xl p-8 max-w-md w-full text-center shadow-2xl shadow-emerald-500/10 animate-drop-ribbon relative overflow-hidden">

            <!-- Floating Balloons Background Elements -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute bottom-2 left-10 w-6 h-8 bg-indigo-500/30 rounded-full balloon-1"></div>
                <div class="absolute bottom-4 right-12 w-6 h-8 bg-emerald-500/30 rounded-full balloon-2"></div>
                <div class="absolute bottom-1 left-1/2 w-5 h-7 bg-cyan-500/30 rounded-full balloon-3"></div>
            </div>

            <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-40 h-20 bg-emerald-500/20 blur-2xl rounded-full pointer-events-none"></div>

            <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex items-center justify-center mx-auto mb-5 text-emerald-400 shadow-inner relative z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div class="inline-block px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-semibold uppercase tracking-wider mb-2 relative z-10">
                🎉 Congratulations!
            </div>

            <h3 id="successSchoolTitle" class="text-xl font-bold text-white mt-1 relative z-10"><?php echo htmlspecialchars($success_message); ?> Registered Successfully!</h3>
            <p class="text-xs text-slate-400 mt-2 leading-relaxed relative z-10">
                Your school details have been successfully received and recorded by Joshtech Innovations.
            </p>

            <div class="mt-8 pt-6 border-t border-slate-800 relative z-10">
                <button type="button" onclick="window.location.href='../index.php'"
                    class="w-full py-3 px-4 rounded-xl text-xs font-semibold bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 shadow-lg shadow-emerald-500/20 transition">
                    Continue
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        const progressBar = document.getElementById('progressBar');

        const fields = [
            'school_name', 'short_name', 'motto', 'address',
            'email', 'phone', 'phone2', 'website', 'current_session', 'current_term'
        ];

        function updateStepUI() {
            for (let i = 1; i <= totalSteps; i++) {
                const section = document.getElementById(`step${i}`);
                const indicator = document.getElementById(`stepIndicator${i}`);
                if (i === currentStep) {
                    section.classList.remove('step-hidden');
                    section.classList.add('step-active');
                    indicator.classList.add('text-indigo-400', 'font-semibold');
                } else {
                    section.classList.remove('step-active');
                    section.classList.add('step-hidden');
                    indicator.classList.remove('text-indigo-400', 'font-semibold');
                }
            }

            progressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
            prevBtn.classList.toggle('hidden', currentStep === 1);
            nextBtn.classList.toggle('hidden', currentStep === totalSteps);
            submitBtn.classList.toggle('hidden', currentStep !== totalSteps);
        }

        function validateCurrentStep() {
            const activeSection = document.getElementById(`step${currentStep}`);
            const requiredInputs = activeSection.querySelectorAll('[required]');
            for (const input of requiredInputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }

        nextBtn.addEventListener('click', () => {
            if (validateCurrentStep() && currentStep < totalSteps) {
                currentStep++;
                updateStepUI();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateStepUI();
            }
        });

        // Live Preview Updater
        function updateLivePreview() {
            const getVal = (id) => document.getElementById(id).value.trim();
            const shortVal = (getVal('short_name') || 'SCH').substring(0, 6).toUpperCase();

            document.getElementById('previewBadge').textContent = shortVal;
            document.getElementById('previewSchoolName').textContent = getVal('school_name') || 'Your School Name';
            document.getElementById('previewAcronym').textContent = shortVal.substring(0, 4);
            document.getElementById('previewMotto').textContent = `"${getVal('motto') || 'School motto will appear here'}"`;
            document.getElementById('previewAddress').textContent = getVal('address') || 'Not provided';
            document.getElementById('previewEmail').textContent = getVal('email') || 'Not provided';

            const p1 = getVal('phone');
            const p2 = getVal('phone2');
            document.getElementById('previewPhones').textContent = p1 ? (p2 ? `${p1} / ${p2}` : p1) : 'Not provided';
            document.getElementById('previewWebsite').textContent = getVal('website') || '---';
            document.getElementById('previewSession').textContent = getVal('current_session') || '----/----';
            document.getElementById('previewTerm').textContent = getVal('current_term') || 'Unselected';
        }

        fields.forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', updateLivePreview);
            document.getElementById(fieldId).addEventListener('change', updateLivePreview);
        });

        // Logo Upload Preview
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const img = document.getElementById('previewLogoImg');
            const acronym = document.getElementById('previewAcronym');
            const fileName = document.getElementById('logoFileName');

            if (file) {
                fileName.textContent = `Selected: ${file.name}`;
                const reader = new FileReader();
                reader.onload = function(evt) {
                    img.src = evt.target.result;
                    img.classList.remove('hidden');
                    acronym.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>