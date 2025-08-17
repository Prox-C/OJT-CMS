<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Selection</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">

</head>


<body class="bg-light">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden rounded-3">
            <!-- Header -->
            <div class="bg-main p-6 text-center">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-tools mr-2"></i> Select Your Skills
                </h1>
                <p class="mt-1 text-indigo-100">
                    Choose at least 3 skills relevant to your program.
                </p>
            </div>
            
            <!-- Skill Selection Form -->
            <form method="POST" action="{{ route('intern.skills.store') }}" class="p-6 space-y-6">
                @csrf
                
                <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                    @foreach($skills as $skill)
                    <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 border border-gray-200 transition-all cursor-pointer">
                        <input type="checkbox" name="skills[]" value="{{ $skill->skill_id }}" 
                               class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-3 block text-gray-700 font-medium">
                            {{ $skill->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
                
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full py-3 px-4 btn-gold text-white font-medium rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Enhanced validation with counter
    const form = document.querySelector('form');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const submitBtn = form.querySelector('button[type="submit"]');
    let selectedCount = 0;
    
    // Update counter on checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            selectedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
            submitBtn.disabled = selectedCount < 3;
            submitBtn.classList.toggle('opacity-50', selectedCount < 3);
            submitBtn.innerHTML = selectedCount < 3 
                ? `<i class="fas fa-exclamation-circle mr-2"></i> Please select ${3 - selectedCount} more` 
                : `<i class="fas fa-save mr-2"></i> Save`;
        });
    });
    
    // Form validation
    form.addEventListener('submit', (e) => {
        if (selectedCount < 3) {
            e.preventDefault();
            alert(`Please select at least 3 skills (${selectedCount}/3 selected)`);
        }
    });
    
    // Initialize button state
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50');
    submitBtn.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> Please select 3 more`;
    </script>
</body>
</html>