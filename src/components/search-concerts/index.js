import React, { Component } from 'react';
import './search-concerts.css';

class SearchConcerts extends Component {
    constructor(props) {
        super(props);

        this.state = {
            form: {
                city_zip: '',
                artist: '',
                genre: '',
                begin_date: '',
                end_date: ''
            }
        }

        this.handleChange = this.handleChange.bind(this);
    }

    handleFormSubmit(event) {
        event.preventDefault();
        const { city_zip, artist, genre, begin_date, end_date } = this.state.form;

        const newState = {
            form: {
                city_zip: '',
                artist: '',
                genre: '',
                begin_date: '',
                end_date: ''

            }
        };
        this.setState(newState);
        this.props.history.push(`/concert-results?artist=${artist}&genre=${genre}&city_zip=${city_zip}&begin_date=${begin_date}&end_date=${end_date}`);
    }

    handleChange(event) {
        const { name, value } = event.target;
        const { form } = this.state;
        this.setState({ form: { ...form, [name]: value } });
    }

    render() {
        const { city_zip, artist, genre, begin_date, end_date } = this.state.form;

        return (
            <div className="results-container">
                <div className="title search-title"> SEARCH CONCERT</div>
                <div className="bottom-content">
                    <form onSubmit={(event) => { this.handleFormSubmit(event) }}>
                        <div className="search-inputs">
                            <input type="text" className="standard-input" autocomplete="off" placeholder="Enter City or Zip Code" name="city_zip" value={city_zip} onChange={this.handleChange} />
                            <input type="text" className="standard-input" autocomplete="off" placeholder="Search by Artist" name="artist" value={artist} onChange={this.handleChange} />
                            <div className="dropdown-center">
                                <select className="dropdown" name="genre" name="genre" value={genre} onChange={this.handleChange} >
                                    <option value="null">Search by Genre</option>
                                    <option value="rock">ROCK</option>
                                    <option value="country">COUNTRY</option>
                                    <option value="pop">POP</option>
                                    <option value="r-b">R&B</option>
                                </select>
                            </div>
                        </div>
                        <div className="title">DATE RANGE</div>
                        <div className="date-holder">
                            <div className='search-inputs'>
                                <input type="date" className="standard-input date" placeholder="Beginning Date" name="begin_date" value={begin_date} onChange={this.handleChange} />
                                <input type="date" className="standard-input date" placeholder="End Date" name="end_date" value={end_date} onChange={this.handleChange} />
                            </div>
                        </div>
                        <div className="buttons">
                            <button className="pink-btn">SEARCH</button>
                        </div>
                    </form>
                </div>
            </div>
        );
    }
}

export default SearchConcerts;
