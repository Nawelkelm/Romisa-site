

document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".step");
    let currentStep = 0;

    // Muestra solo el paso actual
    function showStep(step) {
        steps.forEach((s, index) => {
            s.classList.toggle("active", index === step);
        });
    }

    // Valida los campos requeridos de un paso específico
    function validateStep(step) {
        const stepElement = steps[step];
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

    // Encuentra el primer paso con errores
    function findFirstInvalidStep() {
        for (let i = 0; i < steps.length; i++) {
            if (!validateStep(i)) {
                return i;
            }
        }
        return -1; // Todos los pasos son válidos
    }

    // Maneja el avance al siguiente paso
    function nextStep() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        } else {
            alert("Por favor, complete todos los campos requeridos en este paso antes de continuar.");
        }
    }

    // Maneja el retroceso al paso anterior
    function previousStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }

    // Maneja el envío del formulario
    document.querySelector("form").addEventListener("submit", (event) => {
        const invalidStep = findFirstInvalidStep();
        if (invalidStep !== -1) {
            event.preventDefault(); // Evita el envío del formulario
            currentStep = invalidStep; // Salta al paso con errores
            showStep(currentStep);
            alert("Por favor, complete todos los campos requeridos antes de enviar el formulario.");
        }
    });

    // Asigna eventos a los botones "Siguiente" y "Atrás"
    document.querySelectorAll("button[onclick='nextStep()']").forEach((button) => {
        button.addEventListener("click", nextStep);
    });

    document.querySelectorAll("button[onclick='previousStep()']").forEach((button) => {
        button.addEventListener("click", previousStep);
    });

    // Inicializa mostrando el primer paso
    showStep(currentStep);
});
