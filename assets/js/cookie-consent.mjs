async function submitSimpleCookieSelection(form, submitter) {
    try {
        const url = new URL('/cookie-consent/update', window.location.origin);

        const requestOptions = {
            method: 'POST',
            body: new FormData(form, submitter)
        }

        /**
         * Request object
         * @type {Request}
         */
        const request = new Request(url, requestOptions);

        /**
         * Response object
         * @type {Response}
         */
        const response = await fetch(request);

        if (response.ok) {
            await fetch(window.location.href);

            return Promise.resolve();
        } else {
            new Error(JSON.stringify(response));
            return Promise.reject();
        }
    } catch (e) {
        console.error(e);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const cookieConsent = document.querySelector('.cookie-consent');
    const consentForms = document.querySelectorAll('.cookie-consent__form');


    cookieConsent.querySelector('.js-show-settings').addEventListener('click', function (event) {
        cookieConsent.querySelector('.cookie-consent-simple').style.display = 'none';
        cookieConsent.querySelector('.cookie-consent-detail').style.display = 'block';
    });

    // cookieConsent.querySelectorAll('.js-reject-all-cookies').forEach(item => {
    //     item.addEventListener('click', async (event) => {
    //         const form = item.closest('form');
    //         await submitSimpleCookieSelection(form, event.submitter);
    //     });
    // });
    //
    // cookieConsent.querySelectorAll('.js-accept-all-cookies').forEach(item => {
    //     item.addEventListener('click', async (event) => {
    //         const form = item.closest('form');
    //         await submitSimpleCookieSelection(form, event.submitter);
    //     });
    // });

    cookieConsent.querySelector('.js-consent-simple-form').addEventListener('submit', async function (event) {
        event.preventDefault();
        await submitSimpleCookieSelection(event.target, event.submitter);
    });

    consentForms.forEach((form) => {

        // we got a form
        const submitButtons = form.querySelectorAll('.js-submit-cookie-consent-form');
        const formAction = form.action || location.href;

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            fetch(formAction, {
                method: 'POST',
                body: new FormData(form, event.submitter)
            }).then(function (res) {
                if (res.status >= 200 && res.status < 300) {
                    hideCookieConsentForm(cookieConsent, cookieConsentDialog);
                    dispatchSuccessEvent(event.submitter);
                }
            }).catch(function (error) {
                console.error('Error:', error);
            });
        });

        const cookieConsentDialog = document.querySelector('.cookie-consent-dialog');
        if (cookieConsentDialog) {
            // we got a dialog, show it
            cookieConsentDialog.showModal();
        }
    });
});

function dispatchSuccessEvent(submitter) {
    const formSubmittedEvent = new CustomEvent('cookie-consent.form-submit-successful', {
        detail: submitter
    });
    document.dispatchEvent(formSubmittedEvent);
}

function hideCookieConsentForm(cookieConsent, cookieConsentDialog) {
    if (cookieConsentDialog) {
        cookieConsentDialog.close();
    }
    cookieConsent.remove();
}