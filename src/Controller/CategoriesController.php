<?php
declare(strict_types=1);

namespace App\Controller;

Use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $categories = $this->Categories->find()->contain(['Topics','Topics.Pathways']);

        $this->set(compact('categories'));
    }
    /**
     * API method outputs JSON of the index listing of all topics, and the pathways beneath them
     *
     * @return \Cake\Http\Response|null
     */
    public function api()
    {
        $this->Authorization->skipAuthorization();
        $categories = $this->Categories->find()->contain(['Topics','Topics.Pathways']);
        

        $this->set(compact('categories'));
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
	    $this->Authorization->skipAuthorization();
        $categories = $this->Categories->find('all');
		$category = $this->Categories->get($id, [
            'contain' => ['Topics','Topics.Pathways','Topics.Pathways.Statuses'],
        ]);
        $this->set(compact('categories','category'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        $this->Authorization->authorize($category);
        
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            $sluggedTitle = Text::slug(strtolower($category->name));
            // trim slug to maximum length defined in schema
            $category->slug = $sluggedTitle;
            if ($this->Categories->save($category)) {
            
                return $this->redirect($this->referer());
            }
            echo __('The category could not be saved. Please, try again.');
        }
        $this->set(compact('category'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => [],
        ]);
        $this->Authorization->authorize($category);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            $sluggedTitle = Text::slug(strtolower($category->name));
            // trim slug to maximum length defined in schema
            $category->slug = $sluggedTitle;
            if ($this->Categories->save($category)) {
                print(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            print(__('The category could not be saved. Please, try again.'));
        }
        $this->set(compact('category'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        $this->Authorization->authorize($category);
        if ($this->Categories->delete($category)) {
            print(__('The category has been deleted.'));
        } else {
            print(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
