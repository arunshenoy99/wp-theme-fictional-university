import "./index.scss"
import {useSelect} from "@wordpress/data"
import {useState, useEffect} from 'react'
import apiFetch from '@wordpress/api-fetch'
const __ = wp.i18n.__

wp.blocks.registerBlockType("ourplugin/featured-professor", {
  title: "Professor Callout",
  description: "Include a short description and link to a professor of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    profId: {
      type: 'string'
    }
  },
  edit: EditComponent,
  save: function () {
    return null
  }
})

function EditComponent(props) {
  
  const [thePreview, setThePreview] = useState('')

  useEffect(() => {
    if (props.attributes.profId) {
      updateTheMeta() 

      async function go() {
        const response = await apiFetch({
          path: `/featuredProfessor/v1/getHTML?profId=${props.attributes.profId}`,
          method: 'GET'
        })
        setThePreview(response)
      }
  
      go()
    }
  }, [props.attributes.profId])

  // This useEffect runs when we delete a featured professor block
  useEffect(() => {
    return () => {
      updateTheMeta()
    }
  }, [])

  function updateTheMeta() {
    const profsForMeta = wp.data.select('core/block-editor')
      .getBlocks()
      .filter((x) => x.name == 'ourplugin/featured-professor')
      .map(x => x.attributes.profId)
      .filter((x, index, arr) => arr.indexOf(x) == index)
    console.log(profsForMeta)
    wp.data.dispatch('core/editor').editPost({ meta: { featuredprofessor: profsForMeta} })
  }
  
  // use select sends a network request and for a split second we will have allProfs to be undefined.
  // But as soon as the data store gets updated then the component gets rendered again
  const allProfs = useSelect(select => {
    return select('core').getEntityRecords('postType', 'professor', { per_page: -1 })
  })
  

  if (allProfs == undefined) {
    return <p>Loading....</p>
  }

  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        <select onChange={e => props.setAttributes({ profId: e.target.value })}>
          <option value="">{__('Select a professor', 'featured-professor')}</option>
          {
            allProfs.map((prof) => {
              return (
                <option value={prof.id} selected={props.attributes.profId == prof.id}>
                  {prof.title.rendered}
                </option>
              )
            })
          }
        </select>
      </div>
      <div dangerouslySetInnerHTML={{ __html: thePreview }}></div>
    </div>
  )
}