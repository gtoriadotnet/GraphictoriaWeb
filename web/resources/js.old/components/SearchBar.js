// © XlXi 2021
// Graphictoria 5

import { useState, useRef } from 'react';
import { Link } from 'react-router-dom';
import { useOnClickOutside } from '../helpers/utils.js';

const dropdownLinks = [
	{
		area: 'Games',
		urlbase: '/games/search/'
	},
	{
		area: 'Catalog',
		urlbase: '/catalog/search/'
	},
	{
		area: 'Users',
		urlbase: '/users/search/'
	},
	{
		area: 'Groups',
		urlbase: '/groups/search/'
	}
];

const SearchBar = () => {
	const inputRef = useRef();
	const dropdownRef = useRef();
	const [searchQuery, setSearchQuery] = useState('');
	
	useOnClickOutside([inputRef, dropdownRef], () => setSearchQuery(''));
	
	return (
		<>
			<input type="text" ref={inputRef} className="form-control d-lg-flex graphictoria-search" placeholder="Search" aria-label="Search" aria-describedby="graphictoria-nav-search-button" onChange={ changeEvent => setSearchQuery(changeEvent.target.value) } value={ searchQuery }/>
			
			{
				searchQuery.length !== 0 ?
				<div ref={dropdownRef} id="graphictoria-search-dropdown">
					<ul className="dropdown-menu show" area-labelledby="graphictoria-search-dropdown">
						{
							dropdownLinks.map(({ area, urlbase }, index) => 
								<li key={index}>
									<Link className="dropdown-item py-2" onClick={ () => setSearchQuery('') } to={ urlbase + encodeURIComponent(searchQuery) }>Search <b className="text-truncate graphictoria-search-dropdown-truncate">{searchQuery}</b> in {area}</Link>
								</li>
							)
						}
					</ul>
				</div>
				:
				null
			}
		</>
	);
};

export default SearchBar;