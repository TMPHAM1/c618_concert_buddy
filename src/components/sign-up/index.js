import React, { Component } from 'react';
import './sign-up.css';
import { formatPostData } from '../../helpers';
import axios from 'axios';
import { Link, Redirect } from 'react-router-dom';
import Modal from '../modal';

class SignUp extends Component {
    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);

        this.state = {
            form: {
                email: '',
                name: '',
                password: '',
                passwordCheck: '',
                matching: 'null'
            },
            show: false
        }
    }

    showModal = () => {
        this.setState({
            show: true
        })
    }

    hideModal = () => {
        this.setState({
            show: false
        })
    }

    handleChange(event) {
        const { name, value } = event.target;
        const { form } = this.state;
        const { password, passwordCheck } = this.state.form;
        this.setState({
            form: { ...form, [name]: value }
        });
    }

    async handleFormSubmit(event) {
        event.preventDefault();
        const { email, name, password, passwordCheck } = this.state.form;
        if (password !== passwordCheck) {
            this.setState({
                form: {
                    matching: false
                }
            });
        } else {
            const dataToSend = {
                email: email,
                name: name,
                password: password
            };
            const params = formatPostData(dataToSend);

            await axios.post('api/addUser.php', params).then((resp) => {
                if (resp.data.success) {
                    this.showModal();
                };
            });
        }
    }

    render() {
        const { email, name, password, passwordCheck, matching } = this.state.form;
        const correctStyle = {
            "border": "2px green solid"
        };
        const incorrectStyle = {
            "border": "2px red solid"
        };

        return (
            <div>
                <div className="title">SIGN UP FOR <br />CONCERT BUDDY</div>
                <div className="signup">
                    <form id="signupForm" onSubmit={(event) => { this.handleFormSubmit(event) }}>
                        <div className="signup-inputs">
                            <input type="text" className="signup-input" autocomplete="off" placeholder="Enter Email Address" name="email" value={email} onChange={this.handleChange} />
                            <input type="text" className="signup-input" autocomplete="off" placeholder="Enter Your Name" name="name" value={name} onChange={this.handleChange} />
                        </div>
                        <div className="signup-inputs">
                            <input type="text" style={this.state.form.matching ? correctStyle : incorrectStyle} className="signup-input" placeholder="Choose Password" name="password" type="password" value={password} onChange={this.handleChange} />
                            <input type="text" style={this.state.form.matching ? correctStyle : incorrectStyle} className="signup-input" placeholder="Confirm Password" name="passwordCheck" type="password" value={passwordCheck} onChange={this.handleChange} />
                        </div>
                        <p className={this.state.form.matching ? 'hidden' : 'show'}>  Passwords Are not Matching! </p>
                        <div className="buttons"><button className="pink-btn" >SIGN UP!</button></div>
                    </form>
                </div>
                <Modal show={this.state.show} handleClose={this.hideModal} >
                    <p className="modal-p">You've successfully signed up. Please Log in with your new account</p>
                    <Link to="/sign-in"><div className="btn black-btn">SIGN IN</div></Link>
                </Modal>
            </div>
        );
    }
}

export default SignUp;