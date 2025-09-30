document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".step");
    let currentStep = 0;

    // Muestra solo el paso actual
    function showStep(step) {
        steps.forEach((s, index) => {
            s.classList.toggle("active", index === step);
        });
    }

    // Valida los campos requeridos dentro del paso actual
    function validateStep(step) {
        const stepElement = steps[step];
        // Selecciona inputs, selects y textareas con el atributo 'required'
        const inputs = stepElement.querySelectorAll("input[required], select[required], textarea[required]");
        let valid = true;

        inputs.forEach((input) => {
            if (!input.value.trim()) {
                input.classList.add("error"); // Resalta el campo vacío
                valid = false;
            } else {
                input.classList.remove("error"); // Limpia el error si está completo
            }
        });

        return valid;
    }

    // Avanza al siguiente paso solo si los campos actuales son válidos
    function nextStep() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        } else {
            alert("Por favor, complete todos los campos requeridos en este paso antes de continuar.");
        }
    }

    // Retrocede al paso anterior
    function previousStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }

    // Bloquea el envío del formulario si hay campos incompletos
    document.querySelector("form").addEventListener("submit", (event) => {
        if (!validateStep(currentStep)) {
            event.preventDefault(); // Evita el envío si hay errores
            alert("Por favor, complete todos los campos requeridos antes de enviar el formulario.");
        }
    });

    // Manejo de botones "Siguiente" y "Atrás"
    document.querySelectorAll("button[onclick='nextStep()']").forEach((button) => {
        button.addEventListener("click", nextStep);
    });

    document.querySelectorAll("button[onclick='previousStep()']").forEach((button) => {
        button.addEventListener("click", previousStep);
    });

    // Inicializa el formulario mostrando el primer paso
    showStep(currentStep);
});