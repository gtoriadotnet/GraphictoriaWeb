// © XlXi 2021
// Graphictoria 5

import React, { useState } from 'react';
import { Link, NavLink } from 'react-router-dom';

import styles from '../../sass/Graphictoria.module.scss';

console.log(styles);

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
		<nav className={ `${styles['navbar']} ${styles['graphictoria-navbar']} ${styles['fixed-top']} ${styles['navbar-expand-md']} ${styles['shadow-sm']}` }>
			<div className={ styles['container-sm'] }>
				<NavLink activeClassName={ styles['active'] } className={ styles['navbar-brand'] } to="/">
					<img src="/images/logo.png" alt="Graphictoria" width="43" height="43" draggable="false"/>
				</NavLink>
				<button className={ styles['navbar-toggler'] } type="button" data-bs-toggle="collapse" data-bs-target={ `#${styles['graphictoria-nav']}` } aria-controls={ styles['graphictoria-nav'] } aria-expanded="false" aria-label="Toggle navigation">
					<span className={ styles['navbar-toggler-icon'] }></span>
				</button>
				<div className={ `${styles['collapse']} ${styles['navbar-collapse']}` } id={ styles['graphictoria-nav'] }>
					<ul className={ `${styles['navbar-nav']} ${styles['me-auto']}` }>
						{
							!props.maintenanceEnabled ?
							<>
								<li className={ styles['nav-item'] }>
									<NavLink activeClassName={ styles['active'] } className={ styles['nav-link'] } to="/games">Games</NavLink>
								</li>
								<li className={ styles['nav-item'] }>
									<NavLink activeClassName={ styles['active'] } className={ styles['nav-link'] } to="/catalog">Catalog</NavLink>
								</li>
								<li className={ styles['nav-item'] }>
									<NavLink activeClassName={ styles['active'] } className={ styles['nav-link'] } to="/forum">Forum</NavLink>
								</li>
								<li className={ `${styles['nav-item']} ${styles['dropdown']}` }>
									{/* eslint-disable-next-line */}
									<a className={ `${styles['nav-link']} ${styles['dropdown-toggle']}` } href="#" id={ styles['graphictoria-nav-dropdown'] } role="button" data-bs-toggle="dropdown" area-expanded="false">More</a>
									<ul className={ `${styles['dropdown-menu']} ${styles['graphictoria-nav-dropdown']}` } area-labelledby={ styles['graphictoria-nav-dropdown'] }>
										<li><NavLink activeClassName={ styles['active'] } className={ styles['dropdown-item'] } to="/users">Users</NavLink></li>
										<li><a className={ styles['dropdown-item'] } href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a></li>
									</ul>
								</li>
							</>
							:
							<li className={ styles['nav-item'] }>
								<a className={ styles['nav-link'] } href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a>
							</li>
						}
					</ul>
					{
						!props.maintenanceEnabled ?
						<input type="text" className={ `${styles['form-control']} ${styles['d-lg-flex']} ${styles['graphictoria-search']}` } placeholder="Search" aria-label="Search" aria-describedby="graphictoria-nav-search-button" onChange={ changeEvent => setSearchQuery(changeEvent.target.value) } value={ searchQuery }/>
						:
						null
					}
					
					{
						searchQuery.length !== 0 ?
						<div id={ styles['graphictoria-search-dropdown'] }>
							<ul className={ `${styles['dropdown-menu']} ${styles['show']}` } area-labelledby={ styles['graphictoria-search-dropdown'] }>
								{
									dropdownLinks.map(({ area, urlbase }, index) => 
										<li key={index}>
											<Link activeClassName={ styles['active'] } className={ `${styles['dropdown-item']} ${styles['py-2']}` } onClick={ () => setSearchQuery('') } to={urlbase + encodeURIComponent(searchQuery)}>Search <b className="text-truncate graphictoria-search-dropdown-truncate">{searchQuery}</b> in {area}</Link>
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
						<Link activeClassName={ styles['active'] } className={ `${styles['btn']} ${styles['btn-success']}` } to="/login">Login / Sign up</Link>
						:
						null
					}
				</div>
			</div>
		</nav>
		<div className={ styles['graphictoria-nav-margin'] }>
		</div>
	</>
  );
};

export default Navbar;