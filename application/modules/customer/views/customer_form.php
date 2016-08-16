    <article class="content item-editor-page">
        <div class="title-block">
            <h1 class="title"><?php echo $title;?></h1>
        </div>
            <div class="card card-block">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a aria-controls="home-pills" class="nav-link active"
                        data-target="#basic-pills" data-toggle="tab" href=""
                        role="tab">Grunddaten</a>
                    </li>
                    <li class="nav-item">
                        <a aria-controls="profile-pills" class="nav-link"
                        data-target="#profile-pills" data-toggle="tab" href=""
                        role="tab">weitere Daten</a>
                    </li>
                </ul><!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="basic-pills">
                        <form id="CustomerBase">
                        <input type="hidden" class="customer_id" name="customer_id" id="customer_id" value="<?php if(isset($user->customer_id)) { echo $user->customer_id; } ?>">
                        <input type="hidden" name="activeUser" id="activeUser" value="<?php if(isset($user->active)) { echo $user->active; } ?>">
                        <div class="container-fluid" style="padding-top: 20px;">
                                <div class="col-md-6 col-sm-6 col-xl-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Firmenname:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="company" name="company" value="<?php if(isset($user->company)) { echo $user->company; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Vorname:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="firstname" name="firstname" value="<?php if(isset($user->firstname)) { echo $user->firstname; } ?>">
                                            <label class="firstname err"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Nachname:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="lastname" name="lastname" value="<?php if(isset($user->name)) { echo $user->name; } ?>">
                                            <label class="lastname err"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Strasse:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="street" name="street" value="<?php if(isset($user->street)) { echo $user->street; } ?>">
                                            <label class="street err"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">PLZ / Ort:</label>
                                        <div class="col-sm-3">
                                            <input class="form-control boxed" placeholder="" type="text" id="zipcode" name="zipcode" value="<?php if(isset($user->zipcode)) { echo $user->zipcode; } ?>">
                                            <label class="zipcode err"></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control boxed" placeholder="" type="text" id="city" name="city" value="<?php if(isset($user->city)) { echo $user->city; } ?>">
                                            <label class="city err"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Email:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="email" name="email" value="<?php if(isset($user->email)) { echo $user->email; } ?>">
                                            <label class="email err"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Telefon:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="phone" name="phone" value="<?php if(isset($user->phone)) { echo $user->phone; } ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Mobil:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="mobile" name="mobile" value="<?php if(isset($user->mobile)) { echo $user->mobile; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Telefax:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="fax" name="fax" value="<?php if(isset($user->fax)) { echo $user->fax; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 control-label" for="formGroupExampleInput7">Bemerkungen:</label>
                                        <div class="col-sm-9">
                                            <textarea rows="10" class="form-control" name="comments" id="comments"><?php if(isset($user->comment)) { echo $user->comment; } ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="col-md-6 col-sm-6 col-xl-6">
                                    <fieldset>
                                        <legend>Zugang</legend>

                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Aktiv:</label>
                                            <div class="col-sm-9">
                                                <label>
                                                   <input class="checkbox" type="checkbox" id="active" <?php if(isset($user->active) && $user->active == 1 ) { echo 'checked="checked"'; } ?>>
                                                   <span></span>
                                               </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Passwort:</label>
                                            <div class="col-sm-9">
                                                <input class="form-control boxed" placeholder="" type="text" id="customerPassword" name="customerPassword">
                                                <label class="customerPassword err"></label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label">Passwortstärke:</label>
                                            <div class="col-sm-9">
                                                <meter max="4" id="password-strength-meter"></meter>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6">
                                    <div class="form-group row" style="padding-top: 95px;">
                                        <label class="col-sm-3 form-control-label">Benutzergruppe:</label>
                                        <div class="col-sm-9">
                                            <select class="form-control boxed" type="text" id="group" name="group">
                                                <option value="">Bitte wählen</option>
                                                <?php
                                                foreach ($groups as $key => $value) {
                                                    if(isset($user->group) && $user->group == $value->id ) {
                                                        echo '<option value="'.$value->id.'" selected="selected">'.$value->name.'</option>';
                                                    }else{
                                                        echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-right">
                                            <input type="submit" class="btn btn-primary nextTab" value="Speichern">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="profile-pills">
                        <form id="CustomerExtend">
                        <input type="hidden" class="customer_id" name="customer_id" id="customer_id" value="<?php if(isset($user->customer_id)) { echo $user->customer_id; } ?>">
                            <div class="container-fluid" style="padding-top: 20px;">
                                <div class="col-md-6 col-sm-6 col-xl-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Bundesland:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="province" name="province" value="<?php if(isset($user->province)) { echo $user->province; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Land:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="country" name="country" value="<?php if(isset($user->country)) { echo $user->country; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Webseite:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="website" name="website" value="<?php if(isset($user->website)) { echo $user->website; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Währung:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="currency" name="currency" value="<?php if(isset($user->currency)) { echo $user->currency; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Steuersatz:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="taxrate" name="taxrate" value="<?php if(isset($user->taxrate)) { echo $user->taxrate; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">USt-ID:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="taxid" name="taxid" value="<?php if(isset($user->taxid)) { echo $user->taxid; } ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Kontoinhaber:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="accountholder" name="accountholder" value="<?php if(isset($user->accountholder)) { echo $user->accountholder; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">IBAN:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="iban" name="iban" value="<?php if(isset($user->iban)) { echo $user->iban; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">BIC:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="bic" name="bic" value="<?php if(isset($user->bic)) { echo $user->bic; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 form-control-label">Bank:</label>
                                        <div class="col-sm-9">
                                            <input class="form-control boxed" placeholder="" type="text" id="bankname" name="bankname" value="<?php if(isset($user->bankname)) { echo $user->bankname; } ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row" style="padding-top: 90px;">
                                        <div class="col-sm-12 text-right">
                                            <a class="btn btn-primary SaveAdditional" href="#">Speichern</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </article>
