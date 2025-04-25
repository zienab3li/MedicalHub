<div class="acount-login section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div id="payment-message" class="alert alert-info d-none"></div>
  
                <form id="payment-form">
                    <div id="payment-element"></div>
  
                    <button id="submit" class="btn btn-primary mt-3">
                        <div class="spinner d-none" id="spinner">Processing...</div>
                        <span id="button-text">Pay now</span>
                    </button>
                </form>
  
            </div>
        </div>
    </div>
  </div>
  
  <script src="https://js.stripe.com/v3/"></script>
  <script>
      const stripe = Stripe("{{ config('services.stripe.public_key') }}"); // استخدم المفتاح من config
  
      let elements;
  
      initialize();
  
      document
          .getElementById("payment-form")
          .addEventListener("submit", handleSubmit);
  
      async function initialize() {
          const { clientSecret } = await fetch("{{ route('payments.stripe.intent', $order->id) }}", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
                  "X-CSRF-TOKEN": "{{ csrf_token() }}"
              },
              body: JSON.stringify({})
          }).then((res) => res.json());
  
          elements = stripe.elements({ clientSecret });
  
          const paymentElement = elements.create("payment", {
              layout: "accordion"
          });
  
          paymentElement.mount("#payment-element");
      }
  
      async function handleSubmit(e) {
          e.preventDefault();
          setLoading(true);
  
          const { error } = await stripe.confirmPayment({
              elements,
              confirmParams: {
                  return_url: "{{ route('payments.stripe.confirm', $order->id) }}",
              },
          });
  
          if (error) {
              showMessage(error.message);
          }
  
          setLoading(false);
      }
  
      function showMessage(messageText) {
          const messageContainer = document.querySelector("#payment-message");
          messageContainer.classList.remove("d-none");
          messageContainer.textContent = messageText;
  
          setTimeout(() => {
              messageContainer.classList.add("d-none");
              messageContainer.textContent = "";
          }, 4000);
      }
  
      function setLoading(isLoading) {
          const submitButton = document.querySelector("#submit");
          const spinner = document.querySelector("#spinner");
          const buttonText = document.querySelector("#button-text");
  
          if (isLoading) {
              submitButton.disabled = true;
              spinner.classList.remove("d-none");
              buttonText.classList.add("d-none");
          } else {
              submitButton.disabled = false;
              spinner.classList.add("d-none");
              buttonText.classList.remove("d-none");
          }
      }
  </script>
  