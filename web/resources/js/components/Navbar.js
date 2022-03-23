// © XlXi 2021
// Graphictoria 5

import React from 'react';
import { Link, NavLink } from 'react-router-dom';
import SearchBar from './SearchBar.js';

const Navbar = (props) => {
  return (
    <>
		<nav className="navbar graphictoria-navbar fixed-top navbar-expand-md shadow-sm">
			<div className="container-sm">
				<NavLink className="navbar-brand" to={props.user? `/home` : `/`}>
					<img src="/images/logo.png" alt="Graphictoria" width="43" height="43" draggable="false"/>
				</NavLink>
				<button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#graphictoria-nav" aria-controls="graphictoria-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span className="navbar-toggler-icon"></span>
				</button>
				<div className="collapse navbar-collapse" id="graphictoria-nav">
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
						<>
							<SearchBar />
							{props.user?
							<div className={`flex row`}>
								<div className={`flex row col flex alc`}>Bank: ${props.user.bank}</div>
								<li className="nav-item dropdown col flex alc">
									<button className="btn btn-secondary nav-link dropdown-toggle" href="#" id="graphictoria-nav-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">{props.user.username}</button>
									<ul className="dropdown-menu graphictoria-nav-dropdown" area-labelledby="graphictoria-nav-dropdown">
										<li><NavLink className="dropdown-item" to="/auth/settings">Settings</NavLink></li>
										<li><a className="dropdown-item" href={`/account/logout`}>Logout</a></li>
									</ul>
								</li>
							</div> : <Link className="btn btn-success" to="/login">Login / Sign up</Link>}
						</>
						:
						null
					}
				</div>
			</div>
		</nav>
		<div className="graphictoria-nav-margin">
		</div>
	</>
  );
};

export default Navbar;