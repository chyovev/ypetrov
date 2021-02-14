<main>

    <section class="text textpage" id="container">
        <div class="content-wrapper">

            <h1>Контакт</h1>
            <p>За всякакви предложения или коментари относно сайта, моля, попълнете контактната формата.<br />
            Ако желаете да получите отговор, попълнете полето «E-mail». Ще се свържем с вас във възможно най-кратък срок.</p>
            <form class="contact-form ajax-form" action="<?= Url::generateContactUrl() ?>" method="POST">
                <h3>Контактна форма</h3>
                <input type="text" name="username" id="username" placeholder="*Име" />
                <input type="text" name="email" id="email" placeholder="E-mail" />
                <textarea name="message" id="message" placeholder="*Съобщение"></textarea>
                <div class="error-message none"></div>
                <div class="success-message center green none"></div>
                <input type="submit" value="Изпрати" />
            </form>

        </div>
    </section>

</main>