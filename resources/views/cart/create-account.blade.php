@extends('layouts.app')

@section('content')
    <form onSubmit={this.handleSubmit}>
        <div class="container-fluid">
            <div class="col-md-10 offset-md-1">
                <div class="stepwizard text-center">
                    <div class="stepwizard-row">
                        <div class="stepwizard-step stepactive">
                            <button class="btn btn-circle btn-primary" type="button">1</button>
                            <p><Trans>{t("cart:Account Information")}</Trans></p>
                        </div>
                        <div class="stepwizard-step">
                            <button class="btn btn-circle btn-light" type="button">2</button>
                            <p><Trans>{t("cart:Select Products")}</Trans></p>
                        </div>
                        {/*<div class="stepwizard-step">*/}
                            {/*    <button class="btn btn-circle btn-light" type="button">3</button>*/}
                            {/*    <p>Select Autoship</p>*/}
                            {/*</div>*/}
                        <div class="stepwizard-step">
                            <button class="btn btn-circle btn-light" type="button">3</button>
                            <p><Trans>{t("cart:Shipping Details")}</Trans></p>
                        </div>
                        <div class="stepwizard-step">
                            <button class="btn btn-circle btn-light" type="button">4</button>
                            <p><Trans>{t("cart:Payment Method")}</Trans></p>
                        </div>
                        <div class="stepwizard-step">
                            <button class="btn btn-circle btn-light" type="button">5</button>
                            <p><Trans>{t("cart:Review")}</Trans></p>
                        </div>
                        <hr class="steps-hr"/>
                    </div>
                </div>
                <br/>
                <br/>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="row">
                        <div class="col">
                            <h2 style={{color: '#0090cd', marginBottom: '0px', fontFamily: 'apex-sans-bold'}}>
                                <span style={{fontWeight: 'normal', fontFamily: 'apex-sans-light'}}><Trans>{t("cart:WELCOME")}</Trans></span> {this.state.user_data.name}
                            </h2>
                            <img src={this.state.user_data.profilePicURL} width="256" height="256"/>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col">
                            <div id="country-language" class="text-left">
                                <h2 class="subpage text-center"><Trans>{t("cart:COUNTRY AND LANGUAGE INFORMATION")}</Trans></h2>
                                <div style={{paddingBottom: '30px'}}>
                                    <p style={{fontSize: '15px', fontWeight: 500, color: '#555'}}><Trans>{t("cart:Please select a country and a language for the new team member or customer that will be enrolled")}</Trans>.</p>
                                </div>
                                <div>
                                    <div class="row form-group">
                                        <label class="text-md-right text-sm-left col-md-4 form-label" style={{color: '#3c763d'}}><Trans>{t("cart:Country")}</Trans>*</label>
                                        <div class="col-md-8">
                                            <select class="form-control" ref="country" onChange={(event) => {this.changeCountry(event)}}>
                                            {
                                            this.state.countries.map(country => {
                                            return (
                                            <option value={country.id} selected={(localStorage.getItem('country') && country.id == localStorage.getItem('country')) || country.id == 233}>{country.name}</option>
                                            )
                                            })
                                            }
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="text-md-right text-sm-left col-md-4 form-label" style={{color: '#3c763d'}}>Preferred Language*</label>
                                        <div class="col-md-8">
                                            <select class="form-control" ref="language">
                                                <option value="en">English (en)</option>
                                                <option value="es">Espanol (es)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="account-type" class="text-left">
                                <h2 class="subpage text-center"><Trans>{t("cart:SELECT AN ACCOUNT TYPE")}</Trans></h2>
                                <div class="text-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox1" value="rc" ref="user[usertype]" name="user_type" checked={this.state.accountType === 'rc'} onChange={this.handleOptionChange} />
                                        <label class="form-check-label" htmlFor="inlineCheckbox1">Retail Customer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox2" value="pc" ref="user[usertype]" name="user_type" checked={this.state.accountType === 'pc'} onChange={this.handleOptionChange} />
                                        <label class="form-check-label" htmlFor="inlineCheckbox2">Preferred Customer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox3" value="dc" ref="user[usertype]" name="user_type" checked={this.state.accountType === 'dc'} onChange={this.handleOptionChange} />
                                        <label class="form-check-label" htmlFor="inlineCheckbox3">Distributor</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="sponsor-select" class="text-left">
                                <h2 class="subpage text-center"><b><Trans>{t("cart:PERSONAL SPONSOR INFORMATION")}</Trans></b></h2>
                                <div>
                                    <div class="row form-group">
                                        <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Sponsor Username")}</Trans></label>
                                        <div class="col-md-6 col-9">
                                            <input class="form-control" type="text" ref="user[sponsor_id]" defaultValue={this.state.sponsor_id} disabled={this.state.placement_business_center_id === 1} onKeyUp={(event) => {this.sponsorIdKeyUp(event)}}/>
                                        </div>
                                        <div class="col-md-2 col-3">
                                            <button id="sponsor-search-button" type="button" class="btn btn-primary float-right float-md-none" disabled={this.state.sponsor_id === '' || this.state.placement_business_center_id === 1} onClick={this.verifySponsor}><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <b><i><Trans>{t("cart:Don't Have a Sponsor?")}</Trans></i></b>
                                    <br/>
                                    <p><Trans>{t("cart:Please contact the person who referred you to Ultrra for this information?")}</Trans>.</p>
                                </div>
                                {
                                !this.state.continue_button_disabled && this.state.sponsor !== null ?
                                <div class="text-center">
                                    <h5><img src={this.imageExists('https://admin.ultrra.com/user_images/'+this.state.sponsor.image) ? 'https://admin.ultrra.com/user_images/'+this.state.sponsor.image : 'https://admin.ultrra.com/avatar-big.png'} alt="user-image" style={{width: '100px'}}/><br class="d-md-none"/>{this.state.sponsor.username}, {this.state.sponsor.name}</h5>
                                </div> :
                                <div></div>
                                }
                            </div>
                        </div>
                    </div>
                    {
                    !this.state.continue_button_disabled ?
                    <div>
                        {
                        this.state.accountType !== null && this.state.accountType !== 'rc' ?
                        <div class="row">
                            <div class="col">
                                <div id="placement-sponsor-information" class="text-left">
                                    <h2 class="subpage text-center"><b><Trans>{t("cart:PLACEMENT SPONSOR INFORMATION")}</Trans></b></h2>
                                    <div style={{paddingBottom: '30px'}}>
                                        <p><Trans>{t("cart:If you wish to select your position, click Manual and enter your placement ID number and proceed to select tracking center location Otherwise choose Automatic and Ultrra's system will make the assignment")}</Trans></p>
                                    </div>
                                    <div class="text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="Checkbox1" value="automatic" ref="placement_type" name="placement_type" disabled={this.state.placement_business_center_id !== 0} defaultChecked={this.state.placementType == 'automatic'} onChange={this.handleOptionChange2} />
                                            <label class="form-check-label" htmlFor="Checkbox1"><Trans>{t("cart:Automatic")}</Trans></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="Checkbox2" value="manual" ref="placement_type" name="placement_type" disabled={this.state.placement_business_center_id !== 0} defaultChecked={this.state.placementType == 'manual'} onChange={this.handleOptionChange2} />
                                            <label class="form-check-label" htmlFor="Checkbox2"><Trans>{t("cart:Manual")}</Trans></label>
                                        </div>
                                    </div>
                                    <br/>
                                    {
                                    this.state.placementType == 'manual' ?
                                    <div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Placement ID")}</Trans>#</label>
                                            <div class="col-md-5 col-8">
                                                <input type="text" ref="user[placement_search_id]" name="user[placement_search_id]" class="form-control" defaultValue={this.state.placement_search_id} disabled={this.state.placement_business_center_id !== 0} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-2 col-4">
                                                <button id="placement-search-button" type="button" class={this.state.placement_business_center_id !== 0 ? "disabled btn btn-primary" : "btn btn-primary"} disabled={this.state.placement_business_center_id !== 0} onClick={this.getPlacement}><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Select Placement")}</Trans></label>
                                            <div class="col-md-8">
                                                <select class="form-control" ref="user[placement_id]" name="user[placement_id]" disabled={this.state.placement_business_center_id !== 0} onChange={(event) => this.selectPlacement(event)} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}>
                                                <option>Select Placement</option>
                                                {
                                                this.state.placements.map((placement) => {
                                                return (<option value={JSON.stringify(placement)} selected={this.state.placement_info.id === placement.id}>{placement.business_center}</option>)
                                                })
                                                }
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Select Placement Side")}</Trans></label>
                                            <div class="col-md-8">
                                                <select class="form-control" ref="user[leg]" disabled={this.state.placement_business_center_id !== 0} onChange={(event) => {this.setState({leg: event.target.value === 'auto' ? 'Auto' : event.target.value === 'L' ? 'Left' : 'Right'})}}>
                                                <option value="auto">Auto</option>
                                                <option value="L" selected={this.state.leg === 'L'} disabled={this.state.placement_business_center_id !== 0}>Left</option>
                                                <option value="R" selected={this.state.leg === 'R'} disabled={this.state.placement_business_center_id !== 0}>Right</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div> : <div></div>
                                    }
                                    {
                                    this.state.placement_info ?
                                    <div class="text-center" style={{paddingTop: '30px'}}>
                                        <b>{this.state.placement_info.business_center} - {this.state.leg}</b>
                                    </div> : <div></div>
                                    }
                                </div>
                            </div>
                        </div> : <div></div>
                        }
                        <div class="row">
                            <div class="col">
                                <div id="user-information" class="text-left">
                                    <h4 class="subpage text-center"><b><Trans>{t("cart:Account Information")}</Trans></b></h4>
                                    <br/>
                                    <div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:First Name")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="user[firstname]" name="user[firstname]" defaultValue={this.state.user_data.firstname} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Last Name")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="user[lastname]" name="user[lastname]" defaultValue={this.state.user_data.lastname} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Joint")}</Trans> <Trans>{t("cart:First Name")}</Trans></label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="user[joint_firstname]" name="user[joint_firstname]" defaultValue={this.state.user_data.joint_firstname} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Joint")}</Trans> <Trans>{t("cart:Last Name")}</Trans></label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="user[joint_lastname]" name="user[joint_lastname]" defaultValue={this.state.user_data.joint_lastname} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Username")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="user[username]" name="user[username]" defaultValue={this.state.user_data.username} disabled={this.state.user_data.username !== null} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        {/*<div class="row form-group">*/}
                                            {/*    <label class="text-md-right text-sm-left col-md-4 form-label">Password *</label>*/}
                                            {/*    <div class="col-md-8">*/}
                                                {/*        <input class="form-control" type="password" ref="user[password]" name="user[password]" onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} required/>*/}
                                                {/*        <div class="invalid-feedback"></div>*/}
                                                {/*    </div>*/}
                                            {/*</div>*/}
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Date of Birth")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <DateInput value={this.state.dob} dateFormat="MM/dd/YYYY" disabled={false} locale="en" modifiers={{disabled: {after: maxDate}}} onChange={this.dobChange} onBlur={this.onBlurDob} style={{backgroundImage: 'url("https://cdn0.iconfinder.com/data/icons/market-and-economics-19/48/49-512.png")', backgroundRepeat: 'no-repeat', backgroundPositionY: 'center', backgroundSize: 'calc(.75em + .375rem) calc(.75em + .375rem)', backgroundPosition: 'left calc(.1em + .075rem) center'}}/>
                                                {/*<input type="hidden" ref="user[dateofbirth]" name="user[dateofbirth]" defaultValue={this.state.dob}/>*/}
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        {
                                        this.state.accountType == 'dc' ?
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">SSN/ITIN</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="user[ssn_number]" name="user[ssn_number]" defaultValue={this.state.user_data.ssn_number} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div> : <div></div>
                                        }
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Mobile Phone")}</Trans> #*</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="user[phone]" name="user[phone]" defaultValue={this.state.user_data.phone} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">Email *</label>
                                            <div class="col-md-8">
                                                <input class={this.state.user_data.email !== "" ? "form-control is-disabled" : "form-control"} type="text" ref="user[email]" name="user[email]" value={this.state.user_data.email} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput} disabled={this.state.user_data.email !== ''} required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        {/*<div class="row form-group">*/}
                                            {/*<label class="text-md-right text-sm-left col-md-4 form-label" style={{color: '#3c763d'}}>SIGN-UP FOR ECLUB</label>*/}
                                            {/*<div class="col-md-8">*/}
                                                {/*<div class="form-check">*/}
                                                    {/*<input type="checkbox" class="form-check-input" id="eclubCheckbox" ref="user[eclub_signup]" checked={this.state.eclub} onChange={this.eclubChecked} />*/}
                                                    {/*<label class="form-check-label" htmlFor="eclubCheckbox">I would like to get company updates and receive special promotional emails.</label>*/}
                                                    {/*</div>*/}
                                                {/*/!*<Form.Check label="I would like to get company updates and receive special promotional emails." ref="user_eclub_signup" checked style={{color: '#3c763d'}}/>*!/*/}
                                                {/*</div>*/}
                                            {/*</div>*/}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="user-information" class="text-left">
                                    <h2 class="subpage text-center"><b><Trans>{t("cart:MAIN ADDRESS")}</Trans></b></h2>
                                    <br/>
                                    <div>
                                        {/*<div class="row form-group">*/}
                                            {/*<label class="text-md-right text-sm-left col-md-4 form-label">Contact Name (if different)</label>*/}
                                            {/*<div class="col-md-8">*/}
                                                {/*<input class="form-control" type="text" ref="address[contact_name]" name="address[contact_name]" onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}/>*/}
                                                {/*<div class="invalid-feedback"></div>*/}
                                                {/*</div>*/}
                                            {/*</div>*/}
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Address")}</Trans> 1 *</label>
                                            <div class="col-md-8">
                                                <input type="hidden" ref="address[id]" name="address[id]" defaultValue={this.state.address_data.id}/>
                                                <input class="form-control" type="text" ref="address[address_1]" name="address[address_1]" defaultValue={this.state.address_data.address_1} required onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Address")}</Trans> 2</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="address[address_2]" name="address[address_2]" defaultValue={this.state.address_data.address_2}/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:City")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="address[city]" name="address[city]" defaultValue={this.state.address_data.city} required onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:State")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <select class="form-control" ref="address[state_id]" name="address[state_id]" onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}>
                                                    {
                                                    this.state.states.map(state => {
                                                    return (
                                                    <option value={state.id} selected={state.id == this.state.address_data.state_id}>{state.name}</option>
                                                    )
                                                    })
                                                    }
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label"><Trans>{t("cart:Postal Code")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" ref="address[postcode]" defaultValue={this.state.address_data.postcode} name="address[postcode]" required onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}/>
                                                <div class="invalid-feedback"></div>
                                                <input class="form-control" type="hidden" ref="address[type]" value="normal_address"/>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label" style={{color: '#3c763d'}}><Trans>{t("cart:Country")}</Trans> *</label>
                                            <div class="col-md-8">
                                                <select class="form-control" ref="address[country_id]" disabled={true} name="address[country_id]" onChange={this.changeCountry} onBlur={this.onBlurInput} onKeyUp={this.onBlurInput}>
                                                    {
                                                    this.state.countries.map(country => {
                                                    return (
                                                    <option value={country.id} selected={country.id == this.state.address_data.country_id || country.id == this.refs['country'].value}>{country.name}</option>
                                                    )
                                                    })
                                                    }
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> :
                    <div></div>
                    }
                </div>
            </div>
            {
            !this.state.continue_button_disabled ?
            <div>
                <div class="row">
                    <div class="col-md-10 offset-md-1 col-12">
                        <div class="">
                            <div class="detailsFull text-left">
                                <div><h5 style={{marginBottom: '20px'}}><b><Trans>{t("cart:ACKNOWLEDGEMENT")}</Trans></b></h5></div>
                                <div class="termsbox">
                                    <p class="heading"><strong><Trans>{t("cart:TERMS & CONDITIONS")}</Trans></strong></p>
                                    <p class="paragraph">The use of this site or any other site owned or maintained by Ultrra, a corporation organized and existing under the laws of
                                        the United States of America ("Company") and is governed by the policies, terms and conditions set forth below. Please
                                        read them carefully. Your use of this site signifies your acceptance of the terms and conditions set forth below. Your order
                                        placed on this site signifies your acceptance of the terms and conditions set forth below.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>1. Privacy & Security; Disclosure</strong></p>
                                    <p class="paragraph">
                                        Company's privacy policy may be viewed at http://www.ultrra.com
                                        <br/>
                                        Company reserves the right to modify its privacy policy in its reasonable discretion from time to time.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>2. Payment Methods</strong></p>
                                    <p class="paragraph">
                                        We accept U.S. issued credit and debit cards: <br/>
                                        Visa <br/>
                                        MasterCard <br/>
                                        Discover <br/>
                                        American Express <br/>
                                        <br/>
                                        When placing an order online, you will need: <br/>
                                        The address the card's statement is sent to (billing address).
                                        <br/>
                                        The card number and expiration date. <br/>
                                        The 3 or 4 digit code found only on the card (CVV2 code). <br/>
                                        Credit card orders can be placed online over our 128 bit Secure Socket layer encrypted connection.
                                        <br/>
                                        You are entering into a legally binding agreement with Ultrra. Ultrra.com is the official online store for Ultrra and its affiliates. Ultrra has the registered mailing address of:
                                        <br/>
                                        Ultrra <br/>
                                        10101 Southwest Freeway <br/>
                                        4th Floor <br/>
                                        Houston, TX 77074 USA <br/>
                                        Phone: 888.981.1711 <br/>
                                        Email: cc@ultrra.com <br/>
                                        Hours: 9am-5pm M-F <br/>

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>3. Shipping Policies</strong></p>
                                    <p class="paragraph">
                                        Company ships orders via local courier and will call centers. Depending on product availability, orders are usually
                                        processed for shipment within 1 to 3 business days after custom manufacturing is completed. Custom manufacturing can
                                        take up to 10 business days. Accurate shipping address and phone number are required. Your signature may be required
                                        for delivery. Will call, hand deliveries and pickups are the responsibility of the customer.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>4. Delivery Confirmation</strong></p>
                                    <p class="paragraph">
                                        Because many instances may occur at your delivery address that are beyond our control, you agree that any delivery
                                        confirmation provided by the carrier is deemed sufficient proof of delivery to the card holder, even without a signature.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>5. Return and Cancellation Policy</strong></p>
                                    <p class="paragraph">
                                        All independent distributor package and product sales are final in signed agreement with section four (4) on your enrollment form. Any and all member purchases are non-refundable. The customer 30 day money back guarantee applies from the date of purchase. Customer must notify CustomerCare of order cancellation via email from the same email address on file for their account to cc@ultrra.com
                                        <br/>
                                        <br/>

                                        The following terms apply for all damaged items: <br/>
                                        You must notify Ultrra within 24 hours of package delivery of damages and obtain a Return Merchandize Authorization (RMA) number by contacting the customer support department at cc@Ultrra.com.<br/>
                                        <br/>
                                        An RMA number can ONLY be obtained by contacting the customer service department at cc@ultrra.com.
                                        <br/>
                                        Company cannot process packages marked "Return to Sender." <br/>
                                        Shipping charges and return shipping charges are not refundable in any case.
                                        <br/>
                                        Company is not responsible for lost or stolen items. Company is not responsible for returned items. We recommend all returned items to be sent using some type of delivery confirmation system to ensure proper delivery.
                                        <br/>

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>6. Chargeback Policy</strong></p>
                                    <p class="paragraph">
                                        All references to a "chargeback" refer to a reversal of a credit/debit card charge placed on www.Ultrra.com. There is no
                                        reason for a chargeback to ever be filed. If a credit is due, simply contact us, and we will gladly issue it. Unnecessary
                                        chargebacks are theft and can be prosecuted, and will be prosecuted to the fullest extent of the law. If you feel that your
                                        credit/debit card was used fraudulently on www.ultrra.com, please contact us for immediate resolution.
                                        YOU AGREE THAT YOU WILL NOT CHARGEBACK ANY AMOUNTS CHARGED TO YOUR CREDIT/DEBIT CARD ON THIS
                                        SITE. IF YOU CHARGEBACK A CREDIT/DEBIT CARD CHARGE FOR A PAYMENT INITIATED BY YOU, YOU AGREE
                                        THAT THIS SITE MAY RECOVER THE AMOUNT OF THE CHARGEBACK IN ADDITION TO $200.00 USD BY ANY
                                        MEANS DEEMED NECESSARY, INCLUDING BUT NOT LIMITED TO RECHARGING YOUR CREDIT/DEBIT CARD OR
                                        HAVING THE AMOUNT RECOVERED THROUGH COLLECTIONS BY A COLLECTION AGENCY.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>7. Third Party Interactions</strong></p>
                                    <p class="paragraph">
                                        During use of Company Website, you may enter into correspondence with, purchase goods and/or services from, or
                                        participate in promotions of advertisers or sponsors showing their goods and/or services through the Website. Any such
                                        activity, and any terms, conditions, warranties or representations associated with such activity, are solely between you and
                                        the applicable third-party. Company shall have no liability, obligation or responsibility for any such correspondence,
                                        purchase or promotion between you and any such third party. Company does not endorse any sites on the Internet that are
                                        linked through its Website. Company provides these links to you only as a matter of convenience, and in no event shall
                                        Company be responsible for any content, products, or other materials on or available from such sites. Company provides
                                        products to you pursuant to the terms and conditions of this Agreement. You recognize, however, that certain third-party
                                        providers of ancillary software, hardware or services may require your agreement to additional or different license or other
                                        terms prior to your use of or access to such software, hardware or services.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>8. Ordering Disclaimer</strong></p>
                                    <p class="paragraph">
                                        Your electronic order confirmation, or any form of confirmation, does not signify our acceptance of your order. Company
                                        reserves the right to accept or deny shipment to anyone for any reason. Company reserves the right to require additional
                                        information before processing any order. If an order appears fraudulent in any way, Company reserves the right to cancel
                                        the order, notify the card holder and the proper authorities.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>9. Product Disclaimers; Disclaimers of Warranty</strong></p>
                                    <p class="paragraph">
                                        THE SERVICE AND ALL CONTENT IS PROVIDED TO YOU STRICTLY ON AN "AS IS" BASIS. ALL CONDITIONS,
                                        REPRESENTATIONS AND WARRANTIES, WHETHER EXPRESS, IMPLIED, STATUTORY OR OTHERWISE, INCLUDING,
                                        WITHOUT LIMITATION, ANY IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE,
                                        OR NONINFRINGEMENT OF THIRD PARTY RIGHTS, ARE HEREBY DISCLAIMED TO THE MAXIMUM EXTENT
                                        PERMITTED BY APPLICABLE LAW BY COMPANY.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>10. Limitation of Liability</strong></p>
                                    <p class="paragraph">
                                        IN NO EVENT SHALL EITHER PARTY BE LIABLE TO ANYONE FOR ANY INDIRECT, PUNITIVE, SPECIAL,
                                        EXEMPLARY, INCIDENTAL, CONSEQUENTIAL OR OTHER DAMAGES OF ANY TYPE OR KIND (INCLUDING LOSS OF
                                        DATA, REVENUE, PROFITS, USE OR OTHER ECONOMIC ADVANTAGE) ARISING OUT OF, OR IN ANY WAY
                                        CONNECTED WITH THIS SITE, INCLUDING BUT NOT LIMITED TO THE USE OR INABILITY TO USE THE SITE, OR
                                        FOR ANY CONTENT OBTAINED FROM OR THROUGH THE SITE, ANY INTERRUPTION, INACCURACY, ERROR OR
                                        OMISSION, REGARDLESS OF CAUSE IN ANY INFORMATION CONTAINED HEREIN, EVEN IF THE PARTY FROM WHICH
                                        DAMAGES ARE BEING SOUGHT HAS BEEN PREVIOUSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
                                        Certain states and/or jurisdictions do not allow the exclusion of implied warranties or limitation of liability for incidental,
                                        consequential or certain other types of damages, so the exclusions set forth above may not apply to you.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>11. Notice</strong></p>
                                    <p class="paragraph">
                                        Company may give notice by means of a general notice on the www.ultrra.com Website, electronic mail to your e-mail
                                        address on record in Company's account information, or by written communication sent by first class mail or pre-paid post to
                                        your address on record in Company's account information. Such notice shall be deemed to have been given upon the
                                        expiration of 48 hours after mailing or posting (if sent by first class mail or pre-paid post) or 24 hours after sending (if sent by
                                        e-mail). You may give notice to Company (such notice shall be deemed given when received by Company) at any time by
                                        any of the following: letter delivered by nationally recognized overnight delivery service or first class postage prepaid mail
                                        to Company at the following address: <br/>
                                        <br/>
                                        Ultrra <br/>
                                        10101 Southwest Freeway <br/>
                                        4th Floor <br/>
                                        Houston, TX 77074 USA <br/>

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>12. Modification to Terms</strong></p>
                                    <p class="paragraph">
                                        Company reserves the right to modify the terms and conditions of this Agreement or its policies relating to its products and
                                        services at any time, effective upon posting of an updated version of this Agreement on the www.ultrra.com Website. You
                                        are responsible for regularly reviewing this Agreement. Continued use of the Service after any such changes shall
                                        constitute your consent to such changes.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>13. General</strong></p>
                                    <p class="paragraph">
                                        With respect to U.S. Customers, this Agreement shall be governed by Texas law and controlling United States federal law,
                                        without regard to the choice or conflicts of law provisions of any jurisdiction, and any disputes, actions, claims or causes of
                                        action arising out of or in connection with this Agreement or the Service shall be subject to the exclusive jurisdiction of the
                                        state and federal courts located in Texas. If any provision of this Agreement is held by a court of competent jurisdiction to
                                        be invalid or unenforceable, then such provision(s) shall be construed, as nearly as possible, to reflect the intentions of the
                                        invalid or unenforceable provision(s), with all other provisions remaining in full force and effect. No joint venture,
                                        partnership, employment, or agency relationship exists between you and Company as a result of this agreement or use of
                                        this Website. The failure of Company to enforce any right or provision in this Agreement shall not constitute a waiver of
                                        such right or provision unless acknowledged and agreed to by Company in writing. This Agreement, together with any
                                        applicable Form and policies, comprises the entire agreement between you and Company and supersedes all prior or
                                        contemporaneous negotiations, discussions or agreements, whether written or oral, between the parties regarding the
                                        subject matter contained herein.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>14. Definitions</strong></p>
                                    <p class="paragraph">
                                        As used in this Agreement and in any Order Forms now or hereafter associated herewith: "Agreement" means these online
                                        terms of use, any Order Forms, whether written or submitted online via the www.ultrra.com Website(s), and any materials
                                        available on the Company Website(s) specifically incorporated by reference herein, as such materials, including the terms
                                        of this Agreement, may be updated by Company from time to time in its sole discretion; "Effective Date" means the earlier
                                        of either the date this Agreement is accepted by selecting the "I Accept" option presented on the screen after this
                                        Agreement is displayed, the Effective date on the subscription form or the date you begin purchasing products from this
                                        site; "Order Form(s)" means the form evidencing your purchase from this site and any subsequent order forms submitted
                                        online or in written form, each such Order Form to be incorporated into and to become a part of this Agreement (in the
                                        event of any conflict between the terms of this Agreement and the terms of any such Order Form, the terms of this
                                        Agreement shall prevail); "Company" means collectively Ultrra, a corporation organized and existing under the laws of the
                                        State of Texas doing business as "www.ultrra.com, together with its officers, directors, shareholders, employees, agents and
                                        affiliated companies.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>15. Questions or Additional Information</strong></p>
                                    <p class="paragraph">
                                        If you have questions regarding this Agreement or wish to obtain additional information, please send an e-mail to cc@Ultrra.com.
                                        <br/>
                                        <br/>

                                        © 2011-2020 Ultrra. All Rights Reserved.

                                    </p>

                                </div>
                                <br/>
                                <div class="text-center col-md-8 offset-md-2">
                                    <div>
                                        <div class="row form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="termsCheck" onChange={this.termsChecked}/>
                                            <label class="form-check-label" htmlFor="termsCheck">
                                                <Trans>{t("cart:I agree to the terms and conditions of the Customer Agreement and the Policies and Procedures")}</Trans>.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> :
            <div></div>
            }
            <br/>
            <br/>
            <div class="row">
                <div class="col-md-2 offset-md-5 col-12 text-center">
                    <button id="submit-button" class="btn btn-dark btn-block" type="submit" disabled={this.state.continue_button_disabled || !this.state.termsChecked}><b><Trans>{t("cart:CONTINUE")}</Trans></b></button>
                </div>
            </div>
        </div>
        <br/>
    </form>
    <style>

    </style>
@endsection
@push('js')
    <script>

    </script>
@endpush