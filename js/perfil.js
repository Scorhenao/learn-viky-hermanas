(() => {
    "use strict";

    /* EDICIÓN DEL PERFIL */

    const form = document.getElementById("lv-profile-form");
    const edit = document.getElementById("lv-edit-button");
    const cancel = document.getElementById("lv-cancel-button");
    const actions = document.getElementById("lv-profile-actions");

    const fields = [
        document.getElementById("lv-name"),
        document.getElementById("lv-email")
    ];

    let editing = false;
    let originalValues = fields.map(input => input.value);

    function setEditing(value) {
        editing = value;

        fields.forEach(input => {
            input.readOnly = !value;
            input.required = value;
            input.setCustomValidity("");
        });

        actions.hidden = !value;
        edit.hidden = value;
        cancel.hidden = !value;

        edit.setAttribute("aria-expanded", String(value));
    }

    edit.addEventListener("click", () => {
        originalValues = fields.map(input => input.value);

        setEditing(true);
        fields[0].focus();
    });

    cancel.addEventListener("click", () => {
        fields.forEach((input, index) => {
            input.value = originalValues[index];
        });

        setEditing(false);
        edit.focus();
    });

    fields[0].addEventListener("input", () => {
        fields[0].setCustomValidity("");
    });

    form.addEventListener("submit", event => {
        if (!editing) {
            event.preventDefault();
            return;
        }

        fields.forEach(input => {
            input.value = input.value.trim();
        });

        fields[0].setCustomValidity(
            fields[0].value
                ? ""
                : "Escribe tu nombre completo."
        );

        if (!form.reportValidity()) {
            event.preventDefault();
        }

        /*
         * El POST real continúa.
         * No deshabilitar los campos ni el botón con nombre.
         */
    });

    setEditing(false);

    /* VALIDACIÓN DE CONTRASEÑAS */

    const passwordForm = document.getElementById("lv-password-form");
    const password = document.getElementById("lv-password");

    const confirmation = document.getElementById(
        "lv-password-confirm"
    );

    const passwordError = document.getElementById(
        "lv-password-error"
    );

    function validatePasswords() {
        const mismatch =
            confirmation.value !== ""
            && password.value !== confirmation.value;

        const message = mismatch
            ? "Las contraseñas no coinciden."
            : "";

        confirmation.setCustomValidity(message);

        confirmation.setAttribute(
            "aria-invalid",
            String(mismatch)
        );

        passwordError.textContent = message;
    }

    [password, confirmation].forEach(input => {
        input.addEventListener("input", validatePasswords);
    });

    passwordForm.addEventListener("submit", event => {
        validatePasswords();

        if (!passwordForm.reportValidity()) {
            event.preventDefault();
        }
    });

    /* MOSTRAR / OCULTAR CONTRASEÑA */

    const toggles = [
        ...document.querySelectorAll("[data-password-target]")
    ];

    toggles.forEach(button => {
        const input = document.getElementById(
            button.dataset.passwordTarget
        );

        const originalLabel = button.getAttribute("aria-label");

        button.hidden = false;

        button.addEventListener("click", () => {
            const visible = input.type === "password";

            input.type = visible ? "text" : "password";
            button.dataset.visible = String(visible);

            button.setAttribute(
                "aria-label",
                visible
                    ? originalLabel.replace("Mostrar", "Ocultar")
                    : originalLabel
            );
        });
    });

    /* EVITAR ENVÍOS DUPLICADOS DESDE LA INTERFAZ */

    const forms = [
        ...document.querySelectorAll(".lv-post-form")
    ];

    const submitButtons = [
        ...document.querySelectorAll("[data-pending-text]")
    ];

    const labels = new Map(
        submitButtons.map(button => [
            button,
            button.textContent
        ])
    );

    forms.forEach(currentForm => {
        currentForm.addEventListener("submit", event => {
            if (event.defaultPrevented) {
                return;
            }

            if (currentForm.dataset.pending === "true") {
                event.preventDefault();
                return;
            }

            currentForm.dataset.pending = "true";
            currentForm.setAttribute("aria-busy", "true");

            const button =
                event.submitter
                || currentForm.querySelector("[data-pending-text]");

            if (button) {
                /*
                 * aria-disabled comunica el estado, pero conserva
                 * el name/value que necesita el controlador.
                 */
                button.setAttribute("aria-disabled", "true");
                button.textContent = button.dataset.pendingText;
            }

            currentForm.querySelector(
                "[data-form-status]"
            ).textContent = "Enviando al servidor...";
        });
    });

    /* RESTAURAR LA INTERFAZ AL VOLVER CON EL NAVEGADOR */

    window.addEventListener("pageshow", () => {
        forms.forEach(currentForm => {
            delete currentForm.dataset.pending;

            currentForm.removeAttribute("aria-busy");

            currentForm.querySelector(
                "[data-form-status]"
            ).textContent = "";
        });

        submitButtons.forEach(button => {
            button.removeAttribute("aria-disabled");
            button.textContent = labels.get(button);
        });
    });

    /* LIMPIAR CONTRASEÑAS AL SALIR DE LA PÁGINA */

    window.addEventListener("pagehide", () => {
        passwordForm.reset();

        [password, confirmation].forEach(input => {
            input.type = "password";
        });

        validatePasswords();

        toggles.forEach(button => {
            button.dataset.visible = "false";

            button.setAttribute(
                "aria-label",
                button
                    .getAttribute("aria-label")
                    .replace("Ocultar", "Mostrar")
            );
        });
    });

    /* IMÁGENES: OCULTAR EL ICONO ROTO SI EL ARCHIVO NO EXISTE */

    document.querySelectorAll(
        "[data-optional-image]"
    ).forEach(image => {
        image.addEventListener(
            "error",
            () => {
                image.hidden = true;
            },
            { once: true }
        );

        if (image.complete && image.naturalWidth === 0) {
            image.hidden = true;
        }
    });
})();