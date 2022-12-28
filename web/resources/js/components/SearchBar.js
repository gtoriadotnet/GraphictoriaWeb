/*
	Copyright © XlXi 2022
*/

import { useState, useRef, useEffect } from 'react';

import { buildGenericApiUrl } from '../util/HTTP.js';

const dropdownLinks = [
	{
		area: 'Games',
		urlbase: buildGenericApiUrl('www', 'games/search/')
	},
	{
		area: 'Shop',
		urlbase: buildGenericApiUrl('www', 'shop/search/')
	},
	{
		area: 'Users',
		urlbase: buildGenericApiUrl('www', 'users/search/')
	},
	{
		area: 'Groups',
		urlbase: buildGenericApiUrl('www', 'groups/search/')
	}
];

function useOnClickOutside(refs, handler) {
	useEffect(
		() => {
			const listener = (event) => {
				var shouldReturn = false;
				
				refs.map(
					function(val)
					{
						if (shouldReturn == false && (!val.current || val.current.contains(event.target))) 
						{
							shouldReturn = true;
						}
					}
				);
				
				if(shouldReturn)
				{
					return;
				}
				
				handler(event);
			};
			document.addEventListener('mousedown', listener);
			document.addEventListener('touchstart', listener);
			return () => {
				document.removeEventListener('mousedown', listener);
				document.removeEventListener('touchstart', listener);
			};
		},
		[refs, handler]
	);
}

const SearchBar = () => {
	const inputRef = useRef();
	const dropdownRef = useRef();
	const [searchQuery, setSearchQuery] = useState('');
	
	useOnClickOutside([inputRef, dropdownRef], () => setSearchQuery(''));
	
	return (
		<>
			<input type="text" ref={inputRef} className="form-control d-lg-flex" placeholder="Search" aria-label="Search" aria-describedby="virtubrick-nav-search-button" onChange={ changeEvent => setSearchQuery(changeEvent.target.value) } value={ searchQuery }/>
			
			{
				searchQuery.length !== 0 ?
				<div ref={dropdownRef} id="virtubrick-search-dropdown">
					<ul className="dropdown-menu show" area-labelledby="virtubrick-search-dropdown">
						{
							dropdownLinks.map(({ area, urlbase }, index) => 
								<li key={index}>
									<a className="dropdown-item py-2" onClick={ () => setSearchQuery('') } href={ urlbase + encodeURIComponent(searchQuery) }>Search <b className="text-truncate virtubrick-search-dropdown-truncate">{searchQuery}</b> in {area}</a>
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