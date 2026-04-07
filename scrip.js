// Obtener elementos
const formulario = document.getElementById("formulario");
const mensaje = document.getElementById("mensaje");
const btnCalcular = document.getElementById("btnCalcular");

// Función calcular total
function calcularTotal() {
    const cantidadInput = document.getElementById("cantidad");
    const precioInput = document.getElementById("precio_unitario");
    const totalInput = document.getElementById("total");

    const cantidad = parseFloat(cantidadInput.value);
    const precio = parseFloat(precioInput.value);

    if (isNaN(cantidad) || cantidad <= 0) {
        mensaje.textContent = "Cantidad inválida (mayor a 0) ⚠️";
        mensaje.style.color = "red";
        mensaje.style.background = "#fee2e2";
        mensaje.style.padding = "10px";
        mensaje.style.borderRadius = "8px";
        totalInput.value = "";
        return;
    }

    if (isNaN(precio) || precio <= 0) {
        mensaje.textContent = "Precio inválido (mayor a 0) ⚠️";
        mensaje.style.color = "red";
        mensaje.style.background = "#fee2e2";
        mensaje.style.padding = "10px";
        mensaje.style.borderRadius = "8px";
        totalInput.value = "";
        return;
    }

    const total = cantidad * precio;
    totalInput.value = total.toFixed(2);
    mensaje.textContent = "Total calculado ✅";
    mensaje.style.color = "green";
    mensaje.style.background = "#d1fae5";
    mensaje.style.padding = "10px";
    mensaje.style.borderRadius = "8px";
}

// Evento click para calcular
btnCalcular.addEventListener("click", calcularTotal);

// Registrar venta
formulario.addEventListener("submit", async function(e) {
    e.preventDefault();

    const cliente = document.getElementById("cliente").value.trim();
    const producto = document.getElementById("producto").value.trim();
    const cantidad = document.getElementById("cantidad").value;
    const precio_unitario = document.getElementById("precio_unitario").value;
    const total = document.getElementById("total").value;

    // Validaciones locales
    if (!cliente || !producto) {
        mensaje.textContent = "Cliente y producto son obligatorios ⚠️";
        mensaje.style.color = "red";
        mensaje.style.background = "#fee2e2";
        mensaje.style.padding = "10px";
        mensaje.style.borderRadius = "8px";
        return;
    }

    if (!total || parseFloat(total) <= 0) {
        mensaje.textContent = "Primero calcula el total correctamente ⚠️";
        mensaje.style.color = "red";
        mensaje.style.background = "#fee2e2";
        mensaje.style.padding = "10px";
        mensaje.style.borderRadius = "8px";
        return;
    }

    // Mostrar mensaje de "guardando..."
    mensaje.textContent = "💾 Guardando venta...";
    mensaje.style.color = "#0c5460";
    mensaje.style.background = "#d1ecf1";
    mensaje.style.padding = "10px";
    mensaje.style.borderRadius = "8px";

    // Preparar datos
    const formData = new URLSearchParams();
    formData.append("cliente", cliente);
    formData.append("producto", producto);
    formData.append("cantidad", cantidad);
    formData.append("precio_unitario", precio_unitario);
    formData.append("total", total);

    try {
        const response = await fetch("guardar_venta.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Mensaje de éxito
            mensaje.innerHTML = `🎉 ${result.message}<br>📋 Cliente: ${result.cliente}<br>💰 Total: $${parseFloat(result.total).toFixed(2)}`;
            mensaje.style.color = "#155724";
            mensaje.style.background = "#d4edda";
            mensaje.style.border = "2px solid #28a745";
            mensaje.style.padding = "15px";
            mensaje.style.borderRadius = "8px";
            mensaje.style.fontWeight = "bold";
            
            // Limpiar formulario
            formulario.reset();
            document.getElementById("total").value = "";
            
            // Opcional: Reproducir sonido de éxito
            // new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3').play();
            
        } else {
            // Mensaje de error
            mensaje.innerHTML = `❌ ${result.message}`;
            mensaje.style.color = "#721c24";
            mensaje.style.background = "#f8d7da";
            mensaje.style.border = "2px solid #dc3545";
            mensaje.style.padding = "15px";
            mensaje.style.borderRadius = "8px";
        }
    } catch (error) {
        mensaje.innerHTML = "❌ Error de conexión con el servidor. ¿Apache está corriendo?";
        mensaje.style.color = "#721c24";
        mensaje.style.background = "#f8d7da";
        mensaje.style.border = "2px solid #dc3545";
        mensaje.style.padding = "15px";
        mensaje.style.borderRadius = "8px";
        console.error(error);
    }
});