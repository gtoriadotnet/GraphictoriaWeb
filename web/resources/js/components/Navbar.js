// © XlXi 2021
// Graphictoria 5

import React, { useState } from 'react';
import { Link, NavLink } from 'react-router-dom';

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

const Navbar = (props) => {
  const [searchQuery, setSearchQuery] = useState('');

  return (
    <>
		<nav className="navbar graphictoria-navbar fixed-top navbar-expand-md shadow-sm">
			<div className="container-sm">
				<NavLink className="navbar-brand" to="/">
					<img src="/images/logo.png" alt="Graphictoria" width="43" height="43" draggable="false"/>
				</NavLink>
				<button className="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#graphictoria-nav" aria-controls="graphictoria-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span className="navbar-toggler-icon"></span>
				</button>
				<div className="collapse navbar-collapse">
					<ul className="navbar-nav me-auto">
						{
							!props.maintenanceEnabled ?
							<>
								<li className="nav-item">
									<NavLink className="nav-link" to="/games">Games</NavLink>
								</li>
								<li className="nav-item">
									<NavLink className="nav-link" to="/catalog">Catalog</NavLink>
								</li>
								<li className="nav-item">
									<NavLink className="nav-link" to="/forum">Forum</NavLink>
								</li>
								<li className="nav-item dropdown">
									{/* eslint-disable-next-line */}
									<a className="nav-link dropdown-toggle" href="#" id="graphictoria-nav-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">More</a>
									<ul className="dropdown-menu graphictoria-nav-dropdown" area-labelledby="graphictoria-nav-dropdown">
										<li><NavLink className="dropdown-item" to="/users">Users</NavLink></li>
										<li><a className="dropdown-item" href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a></li>
									</ul>
								</li>
							</>
							:
							<li className="nav-item">
								<a className="nav-link" href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a>
							</li>
						}
					</ul>
					{
						!props.maintenanceEnabled ?
						<input type="text" className="form-control d-lg-flex graphictoria-search" placeholder="Search" aria-label="Search" aria-describedby="graphictoria-nav-search-button" onChange={ changeEvent => setSearchQuery(changeEvent.target.value) } value={ searchQuery }/>
						:
						null
					}
					
					{
						searchQuery.length !== 0 ?
						<div id="graphictoria-search-dropdown">
							<ul className="dropdown-menu show" area-labelledby="graphictoria-search-dropdown">
								{
									dropdownLinks.map(({ area, urlbase }, index) => 
										<li key={index}>
											<Link className="dropdown-item py-2" onClick={ () => setSearchQuery('') } to={urlbase + encodeURIComponent(searchQuery)}>Search <b className="text-truncate graphictoria-search-dropdown-truncate">{searchQuery}</b> in {area}</Link>
										</li>
									)
								}
							</ul>
						</div>
						:
						null
					}
					
					{
						!props.maintenanceEnabled ?
						<Link className="btn btn-success" to="/login">Login / Sign up</Link>
						:
						null
					}
				</div>
			</div>
		</nav>
		<div className="graphictoria-nav-margin">
		</div>
		<div className="offcanvas offcanvas-end" tabIndex="-1" id="graphictoria-nav" aria-labelledby="graphictoria-nav">
			<div className="offcanvas-header d-flex">
				<div className="d-flex me-auto">
					<a className="btn btn-success btn-sm" href="/login">Login</a>
					<p className="text-muted my-auto mx-1">OR</p>
					<a className="btn btn-primary btn-sm" href="/register">Sign up</a>
				</div>
				<button type="button" className="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			</div>
			<div className="offcanvas-body">
				egg
			</div>
		</div>
	</>
  );
};

export default Navbar;