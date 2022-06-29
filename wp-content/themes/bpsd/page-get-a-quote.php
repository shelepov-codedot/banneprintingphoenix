<?php

get_header();
?>

    <section class="get-quote">
        <div class="get-quote__title">Get a Quote</div>
        <hr class="get-quote__line">
    </section>

    <section class="section-form">
        <form method="post" class="feedback-form" id="get-a-quote" enctype="multipart/form-data">
            <div class="form-field__container">
                <div class="form-field__success"></div>

                <div class="form-field__wrapper">
                    <div class="form-group">
                        <input type="text" placeholder="First and Last Name" name="name" required>
                    </div>
                </div>
                <div class="form-field__wrapper">
                    <div class="form-group">
                        <input type="text" placeholder="E-mail" name="email" required>
                    </div>
                </div>
                <div class="form-field__wrapper">
                    <div class="form-group">
                        <input type="text" placeholder="Product Type" name="type">
                    </div>
                </div>
                <div class="form-field__wrapper">
                    <div class="form-upload">
                        <input type="file" id="form-upload__actual-btn" name="uploaded_file[]" accept="image/jpeg, image/jpg, image/psb, image/png, image/tiff, image/pdf, image/ai, image/eps, image/svg, image/indd" multiple hidden/>

                        <label id="form-upload__btn" for="actual-btn">Upload File</label>

                        <span class="form-file__selected">No file selected</span>
                    </div>
                    <div class="form-upload__image">
                    </div>
                    <div class="form-upload__image-status"></div>
                </div>

                <div class="form-filed__wrapper">
                    <div class="form-group form-group__textarea">
                        <textarea placeholder="Message" minlength="0" maxlength="524288" style="height:86px" name="message"></textarea>
                    </div>
                </div>

                <div class="form-submit__button">
                    <input type="submit" value="Submit">
                </div>
            </div>
        </form>
    </section>

<?php
get_footer();